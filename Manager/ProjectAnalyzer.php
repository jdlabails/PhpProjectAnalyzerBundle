<?php

namespace JD\PhpProjectAnalyzerBundle\Manager;

use JD\PhpProjectAnalyzerBundle\Entities\Analyze;
use JD\PhpProjectAnalyzerBundle\Traits;
use Symfony\Component\Translation\DataCollectorTranslator as Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Classe basique regroupant les fonctions utilisées dans l'index
 *
 * @author Jean-David Labails <jd.labails@gmail.com>
 */
class ProjectAnalyzer
{
    use Traits\ViewHelper, Traits\ScoreCalculator, Traits\ParamReader;

    private $dirRoot;
    private $parameters;
    private $reportPath;

    private $oAnalyze;
    private $qualityInfo;
    private $testInfo;

    private $translator;

    /**
     * Init ppa
     * @param type $configGlobale
     * @param Translator $translator
     */
    public function __construct($configGlobale, TranslatorInterface $translator, $rootDir)
    {
        // traduction
        $this->translator = $translator;

        // web ppa path
        $this->dirRoot = $rootDir.'/web/ppa';

        // report path
        $this->reportPath = $this->dirRoot.'/reports';

        // parameters
        $this->parameters = $configGlobale;

        // l'objet analyse
        $this->oAnalyze = new Analyze();
        $this->oAnalyze
            ->setLang($translator->getLocale())
            ->setSymfonyVersion($this->getSymfonyVersion())
            ->setNbNamespace($this->extractFromLoc('namespaces'))
            ->setNbClasses($this->extractFromLoc('classes'))
            ->setNbMethod($this->extractFromLoc('methods'))
            ->setLoc((int) $this->extractFromLoc('loc'));

        // Exploits analysis
        $this->count();
        $this->setSecurityInfo();
        $this->setQualityInfo();
        $this->setAnalysisTimeInfo();
        $this->exploitTestReport();
        $this->calculateScore();
    }

    /**
     * il faudra mettre la trad
     *
     * @return type
     */
    public function getTabAvailableAnalysis()
    {
        return [
            'test'      => 'details.libelle.test',
            'md'        => 'details.libelle.phpmd',
            'cpd'       => 'details.libelle.phpcpd',
            'cs'        => 'details.libelle.cs',
            'loc'       => 'details.libelle.phploc',
            'docs'      => 'details.libelle.phpdoc',
            'depend'    => 'details.libelle.phpdepend',
            'security'  => 'details.libelle.security',
        ];
    }

    /**
     * Retourne l'objet analyse
     * @return analyze
     */
    public function getAnalyze()
    {
        // lorsque cette methode est appelee, on en profite pour historiser si enable
        if ($this->isEnable('histo', true)) {
            $this->historise();
        }

        return $this->oAnalyze;
    }

    /**
     * Retourne les infos pour la vue sur les tests
     *
     * @return array
     */
    public function getTestInfo()
    {
        return $this->testInfo;
    }

    /**
     * Retroune les infos pour la vue sur les metriques de qualité
     * @return type
     */
    public function getQualityInfo()
    {
        return $this->qualityInfo;
    }

    /**
     * Vérifie si une analyse est en cours par présence du jeton
     * @return type
     */
    public function isAnalyzeInProgress()
    {
        return file_exists($this->dirRoot.'/jetons/jetonAnalyse');
    }

    /**
     * Recupere le contenu du rapport
     *
     * @param string $file chemin du fichier
     *
     * @return array($txt, $vide) contenu du rapport et boolean si vide ou pas
     */
    public function getReport($file)
    {
        $txt = $this->translator->trans('details.noReport').' :(';
        $vide = false;
        if (file_exists($file)) {
            $txt = file_get_contents($file);

            if (trim($txt) == '') {
                $vide = true;
                $txt = $this->translator->trans('details.emptyReport').' :D';
            }
        }

        return [$txt, $vide];
    }

    /**
     * Set info from security analysis
     */
    protected function setSecurityInfo()
    {
        $securityAnalyse = $this->analyzeReport('SECURITY', false, '[OK] 0 packages have known vulnerabilities');

        $this->oAnalyze->setSecuritySuccess($securityAnalyse['SECURITY']['summary'] === 'ok');
    }

    protected function getSymfonyVersion()
    {
        $reportFilePath = $this->reportPath.'/SYMFONY/report.txt';
        $report = $this->getReport($reportFilePath);

        return substr($report[0], 8, 5);
    }

    /**
     * Set info from quality analysis
     */
    protected function setQualityInfo()
    {
        $csAnalyse = $this->analyzeReport('CS');

        $this->oAnalyze->setCsSuccess($csAnalyse['CS']['summary'] === 'ok');

        $ccMethod = number_format((float) $this->extractFromLoc('ccnByNom'), 2, ',', ' ');

        $this->qualityInfo =
            $csAnalyse +
            $this->analyzeReport('MD') +
            $this->analyzeReport('CPD', false, '0.00% duplicated lines') +
            ['ccMethod' => $ccMethod]
        ;
    }

    /**
     * Exploit les rapports de test unitaire
     * @return type
     */
    protected function exploitTestReport()
    {
        $res = [
            'ok'            => false,
            'nbTest'        => '/',
            'nbAssertions'  => '/',
            'date'          => '/',
            'exeTime'       => '/',
            'exeMem'        => '/',
            'dateTimeCC'    => '/',
            'ccClasse'      => '/',
            'ccMethod'      => '/',
            'ccLine'        => '/',
            'report'        => '',
            'cmd'           => '',
            'cmdManuelle'   => '',
        ];

        $testReportFile = $this->reportPath.'/TEST/report.txt';
        if (file_exists($testReportFile)) {
            $res['report'] = $this->adaptPhpUnitReport($testReportFile);

            $res['date'] = $this->getDateGeneration($testReportFile);

            $lines = file($testReportFile);

            if ($this->parameters['test']['lib'] == 'phpunit') {
                foreach ($lines as $l) {
                    // si on est sur la ligne des metrique d'execution du test
                    // Time: 6.8 minutes, Memory: 141.00Mb
                    if (strpos($l, 'Time') !== false && strpos($l, 'Memory') !== false) {
                        list($t, $m) = explode(',', $l);
                        $res['exeTime'] = explode(':', $t)[1];
                        $res['exeMem']  = explode(':', $m)[1];
                    }

                    // output of test : "[30;42mOK (40 tests, 123 assertions)[0m "
                    if (stripos($l, 'test') !== false && stripos($l, 'assertion') !== false) {
                        $res['ok'] = strpos($l, 'OK') !== false;

                        if ($res['ok']) {
                            list($t, $a) = explode(',', $l);

                            $nb = explode('(', $t)[1];
                            $res['nbTest'] = str_ireplace('tests', '', $nb);

                            $nb = explode(')', $a)[0];
                            $res['nbAssertions'] = str_ireplace('assertions', '', $nb);
                        } else {
                            $lineTab = explode(',', $l);

                            $res['nbTest'] = explode(':', $lineTab[0])[1];

                            $res['nbAssertions'] = explode(':', $lineTab[1])[1];
                        }
                    }
                }

                $covReportFile = $this->reportPath.'/TEST/coverage.txt';
                if (file_exists($covReportFile)) {
                    $res['dateTimeCC'] = $this->getReadableDateTime(filemtime($covReportFile));

                    $lines = file($covReportFile);
                    foreach ($lines as $k => $v) {
                        if (strpos($v, 'Summary:') !== false) {
                            $res['ccClasse'] = explode(':', $lines[$k+1])[1];
                            $res['ccMethod'] = explode(':', $lines[$k+2])[1];
                            $res['ccLine']   = explode(':', $lines[$k+3])[1];
                            $res['ccLine']   = explode('(', $res['ccLine'])[0];

                            break;
                        }
                    }
                }
            } // phpunit

            if ($this->parameters['test']['lib'] == 'atoum' && count($lines) > 2) {
                $runningDurationLine = self::findAtoumRunningDuration($lines);

                $res['exeTime'] = explode(':', $lines[$runningDurationLine])[1];

                //Success (4 tests, 40/40 methods, 0 void method, 0 skipped method, 265 assertions)!
                $line = $lines[$runningDurationLine+1];
                $res['ok'] = strpos($line, 'Success') !== false;
                if ($res['ok']) {
                    $items = explode(',', $line);

                    $nb = explode('(', $items[0])[1];
                    $res['nbTest'] = str_ireplace('tests', '', $nb);

                    $res['nbAssertions'] = str_ireplace('assertions)!', '', array_pop($items));

                    foreach ($lines as $l) {
                        if (strpos($l, 'Code coverage value:') !== false) {
                            $res['ccLine'] = str_ireplace('> Code coverage value: ', '', $l);
                        }
                    }

                    $res['dateTimeCC'] = $this->getReadableDateTime(filemtime($testReportFile));
                } else {
                }
            } // atoum
        }

        $cmdFile = $this->reportPath.'/TEST/cmd.txt';
        if (file_exists($cmdFile)) {
            $res['cmd'] = file_get_contents($cmdFile);
        }

        $cmdManuelleFile = $this->reportPath.'/TEST/cmdManuelle.txt';
        if (file_exists($cmdManuelleFile)) {
            $res['cmdManuelle'] =  file_get_contents($cmdManuelleFile);
        }

        $this->oAnalyze
            ->setTuSuccess($res['ok'])
            ->setCov($res['ccLine']);

        $this->testInfo = $res;
    }

    /**
     * Read analysis reports
     * @return array
     */
    public function getReportInfo()
    {
        $tabReports = array('MD', 'CS', 'CPD', 'DEPEND', 'LOC', 'DOCS', 'SECURITY', 'SYMFONY');

        foreach ($tabReports as $report) {
            list($reportTxt, $vide) = $this->getReport($this->reportPath.'/'.$report.'/report.txt');
            $res[$report] = [
                'date'      => $this->getDateGeneration($this->reportPath.'/'.$report.'/report.txt'),
                'report'    => $reportTxt,
                'ok'        => $vide,
            ];

            if ($report == 'CPD') {
                $res[$report]['ok'] = strpos($reportTxt, '0.00% duplicated lines') !== false;
            }

            $cmdFile = $this->reportPath.'/'.$report.'/cmd.txt';
            $res[$report]['cmd'] = '';
            if (file_exists($cmdFile)) {
                $res[$report]['cmd'] = file_get_contents($cmdFile);
            }

            $cmdManuelleFile = $this->reportPath.'/'.$report.'/cmdManuelle.txt';
            $res[$report]['cmdManuelle'] = '';
            if (file_exists($cmdManuelleFile)) {
                $res[$report]['cmdManuelle'] = file_get_contents($cmdManuelleFile);
            }

            if ($report == 'CS') {
                $cmdRepFile = $this->reportPath.'/'.$report.'/cmdRep.txt';
                $res[$report]['cmdRep'] = '';
                if (file_exists($cmdRepFile)) {
                    $res[$report]['cmdRep'] = file_get_contents($cmdRepFile);
                }
            }
        }

        return $res;
    }

    /**
     * Retourn une tableau associatif avec les comptage de fichiers
     *
     * @return type
     */
    protected function count()
    {
        $nbCSS = $this->getCountFile('nbCSS.txt');
        $nbLibCSS = $this->getCountFile('nbLibCSS.txt');
        $nbJS = $this->getCountFile('nbJS.txt');
        $nbLibJS = $this->getCountFile('nbLibJS.txt');

        $this->oAnalyze
            ->setNbDir($this->getCountFile('nbDossier.txt'))
            ->setNbBundles($this->getCountFile('nbBundle.txt'))
            ->setNbFile($this->getCountFile('nbFichier.txt'))
            ->setNbPhpFile($this->getCountFile('nbPHP.txt'))
            ->setNbTwig($this->getCountFile('nbTwig.txt'))
            ->setNbCSSFile($nbCSS - $nbLibCSS)
            ->setNbCSSLib($nbLibCSS)
            ->setNbJSFile($nbJS - $nbLibJS)
            ->setNbJSLib($nbLibJS);
    }

    /**
     * Extract info from phploc report
     * @param type $param
     * @return type
     */
    protected function extractFromLoc($param)
    {
        return $this->extractFromXmlReport($param, '/LOC/phploc.xml');
    }

    /**
     * Recupere le contenu d'un count file
     * @param type $file
     * @return type
     */
    protected function getCountFile($file)
    {
        $txt = '';
        $path = $this->reportPath.'/COUNT/'.$file;
        if (file_exists($path)) {
            $txt = file_get_contents($path);
        }

        return trim($txt);
    }

    /**
     * Renvoi la date de derniere modif du fichier
     *
     * @param type $file
     *
     * @return string
     */
    protected function getDateGeneration($file)
    {
        if (file_exists($file)) {
            return $this->translator->trans('details.generatedOn').' '.$this->getReadableDateTime(filemtime($file));
        }

        return $this->translator->trans('details.notGenerated');
    }

    /**
     * Analysis un rapport
     *
     * @param type $prefix
     * @param type $goodIfEmpty
     * @param type $goodIfContains
     *
     * @return type
     */
    protected function analyzeReport($prefix, $goodIfEmpty = true, $goodIfContains = '')
    {
        $res = [];
        $txt = '';
        $report = $this->reportPath.'/'.$prefix.'/report.txt';
        if (file_exists($report)) {
            $txt = trim(file_get_contents($report));
        }

        $res[$prefix] = ['report' => $txt, 'summary' => 'ko'];

        if (($goodIfEmpty && $txt == '') || (!empty($goodIfContains) && strpos($txt, $goodIfContains) !== false)) {
            $res[$prefix]['summary'] = 'ok';
        }

        // cas particulier de mess detector
        if ($prefix == 'MD') {
            $res['cc10'] = substr_count($txt, 'has a Cyclomatic Complexity of');
        }

        return $res;
    }

    /**
     * Extrait une info d'un xml et la renvoi
     *
     * @param string $cle balise xml recherchee
     * @param string $reportFilePath chemin à l'intéreur du dossier report
     *
     * @return string
     */
    protected function extractFromXmlReport($cle, $reportFilePath)
    {
        $file = $this->reportPath.$reportFilePath;
        if (file_exists($file)) {
            $xml = simplexml_load_file($file);

            return $xml->$cle;
        }

        return '';
    }

    /**
     * Lit la date et le temps d'execution de l'analyse
     */
    protected function setAnalysisTimeInfo()
    {
        $file = $this->dirRoot.'/jetons/timeAnalyse';
        if (file_exists($file)) {
            $this->oAnalyze
                ->setDateTime(filemtime($file))
                ->setExecTime((int) file_get_contents($file));
        }
    }

    protected static function findAtoumRunningDuration(array $lines)
    {
        foreach ($lines as $index => $line) {
            if (strpos($line, 'Running duration:')) {
                return $index;
            }
        }

        return false;
    }
}
