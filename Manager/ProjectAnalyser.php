<?php

namespace JD\PhpProjectAnalyzerBundle\Manager;

use JD\PhpProjectAnalyzerBundle\Entities\Analyze;
use JD\PhpProjectAnalyzerBundle\Traits\VisualizerTrait;
use JD\PhpProjectAnalyzerBundle\Traits\ScoreManagerTrait;
use JD\PhpProjectAnalyzerBundle\Traits\HistoManagerTrait;
use JD\PhpProjectAnalyzerBundle\Traits\ParamManagerTrait;

/**
 * Classe basique regroupant les fonctions utilisées dans l'index
 *
 * @author Jean-David Labails <jd.labails@gmail.com>
 */
class ProjectAnalyser
{
    use VisualizerTrait, ScoreManagerTrait, HistoManagerTrait, ParamManagerTrait;

    private $dirRoot;
    private $parameters;
    private $reportPath;

    private $oAnalyze;

    private $translator;

    /**
     * Init ppa
     * @param type $configGlobale
     * @param type $translator
     */
    public function __construct($configGlobale, $translator)
    {
        $this->translator = $translator;
        // qq chemin
        $this->dirRoot = $configGlobale['reportPath'];
        $this->reportPath = $configGlobale['reportPath'].'/reports';

        // les parameters
        $this->parameters = $configGlobale;

        // les libelles de l'appli
        $availableLang = array('en', 'fr');
        $lang = $this->getParam('lang');
        $lang = in_array($lang, $availableLang) ? $lang : 'en';

        // l'objet analyse
        $this->oAnalyze = new Analyze();
        $this->oAnalyze
            ->setLangue($this->parameters['lang'])
            ->setNbNamespace($this->extractFromLoc('namespaces'))
            ->setNbClasses($this->extractFromLoc('classes'))
            ->setNbMethod($this->extractFromLoc('methods'))
            ->setLoc((int) $this->extractFromLoc('loc'));
            ;

        $this->getCount();
        $this->getAnalyseInfo();
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
     * Retourn une tableau associatif avec les comptage de fichiers
     *
     * @return type
     */
    public function getCount()
    {
        $res = [
            'nbDossier'     => $this->getCountFile('nbDossier.txt'),
            'nbFichier'     => $this->getCountFile('nbFichier.txt'),
            'nbPHP'         => $this->getCountFile('nbPHP.txt'),
            'nbTwig'        => $this->getCountFile('nbTwig.txt'),
            'nbBundle'      => $this->getCountFile('nbBundle.txt'),
        ];

        $nbCSS = $this->getCountFile('nbCSS.txt');
        $nbLibCSS = $this->getCountFile('nbLibCSS.txt');
        $nbJS = $this->getCountFile('nbJS.txt');
        $nbLibJS = $this->getCountFile('nbLibJS.txt');

        $res['nbLibCSS']    = $nbLibCSS;
        $res['nbCSS']       = $nbCSS - $nbLibCSS;
        $res['nbLibJS']     = $nbLibJS;
        $res['nbJS']        = $nbJS - $nbLibJS;

        $this->oAnalyze
            ->setNbDir($res['nbDossier'])
            ->setNbBundles($res['nbBundle'])
            ->setNbFile($res['nbFichier'])
            ->setNbPhpFile($res['nbPHP'])
            ->setNbTwig($res['nbTwig'])
            ->setNbCSSFile($res['nbCSS'])
            ->setNbCSSLib($res['nbLibCSS'])
            ->setNbJSFile($res['nbJS'])
            ->setNbJSLib($res['nbLibJS'])
            ;

        return $res;
    }

    /**
     * Return info from quality analysis
     * @return array
     */
    public function getQualityInfo()
    {
        $csAnalyse = $this->analyseReport('CS');

        $this->oAnalyze->setCsSuccess($csAnalyse['CS']['summary'] === 'ok');

        $ccMethod = number_format((float) $this->extractFromLoc('ccnByNom'), 2, ',', ' ');

        return
            $csAnalyse +
            $this->analyseReport('MD') +
            $this->analyseReport('CPD', false, '0.00% duplicated lines') +
            ['ccMethod' => $ccMethod]
        ;
    }

    /**
     * Exploit les rapports de test unitaire
     * @return type
     */
    public function exploitTestReport()
    {
        $res = [
            'ok'            => false,
            'nbTest'        => '/',
            'nbAssertions'  => '/',
            'date'          => '/',
            'exeTime'       => '/',
            'exeMem'        => '/',
            'dateTimeCC'    => '/',
            'coverage'      => '/',
            'ccClasse'      => '/',
            'ccMethod'      => '/',
            'ccLine'        => '/',
            'report'        => '',
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
                        list($neverMind, $res['exeTime']) = explode(':', $t);
                        list($neverMind, $res['exeMem']) = explode(':', $m);
                    }

                    // [30;42mOK (40 tests, 123 assertions)[0m
                    if (stripos($l, 'test') !== false && stripos($l, 'assertion') !== false) {
                        $res['ok'] = strpos($l, 'OK') !== false;

                        if ($res['ok']) {
                            list($t, $a) = explode(',', $l);

                            list($neverMind, $nb) = explode('(', $t);
                            $res['nbTest'] = str_ireplace('tests', '', $nb);

                            list($nb, $neverMind) = explode(')', $a);
                            $res['nbAssertions'] = str_ireplace('assertions', '', $nb);
                        } else {
                            list($t, $a, $neverMind) = explode(',', $l);

                            list($neverMind, $res['nbTest']) = explode(':', $t);

                            list($neverMind,  $res['nbAssertions']) = explode(':', $a);
                        }
                    }
                }

                $covReportFile = $this->reportPath.'/TEST/coverage.txt';
                if (file_exists($covReportFile)) {
                    $res['dateTimeCC'] = $this->getReadableDateTime(filemtime($covReportFile));

                    $lines = file($covReportFile);
                    foreach ($lines as $k => $v) {
                        if (strpos($v, 'Summary:') !== false) {
                            list($neverMind, $res['ccClasse']) = explode(':', $lines[$k+1]);
                            list($neverMind, $res['ccMethod']) = explode(':', $lines[$k+2]);
                            list($neverMind, $res['ccLine']) = explode(':', $lines[$k+3]);
                            list($res['ccLine'], $neverMind) = explode('(', $res['ccLine']);

                            break;
                        }
                    }
                }
            } // phpunit

            if ($this->parameters['test']['lib'] == 'atoum') {
                $nbLines = count($lines);
                list($neverMind, $res['exeTime']) = explode(':', $lines[$nbLines-2]);

                //Success (4 tests, 40/40 methods, 0 void method, 0 skipped method, 265 assertions)!
                $line = $lines[$nbLines-1];
                $res['ok'] = strpos($line, 'Success') !== false;
                if ($res['ok']) {
                    $items = explode(',', $line);

                    list($neverMind, $nb) = explode('(', $items[0]);
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
            $res['cmd'] =  file_get_contents($cmdFile);
        }

        $cmdManuelleFile = $this->reportPath.'/TEST/cmdManuelle.txt';
        if (file_exists($cmdManuelleFile)) {
            $res['cmdManuelle'] =  file_get_contents($cmdManuelleFile);
        }

        $this->oAnalyze
            ->setTuSuccess($res['ok'])
            ->setCov($res['coverage'])
            ;

        return $res;
    }

    /**
     * Lit les rapports d'analyse
     * @return array
     */
    public function getReportInfo()
    {
        $tabReports = array('MD', 'CS', 'CPD', 'DEPEND', 'LOC', 'DOCS');

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
     * @param type $file
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
     * @param type $prefix
     * @param type $goodIfEmpty
     * @param type $goodIfContains
     * @return type
     */
    protected function analyseReport($prefix, $goodIfEmpty = true, $goodIfContains = '')
    {
        $res = array();
        $txt = '';
        $report = $this->reportPath.'/'.$prefix.'/report.txt';
        if (file_exists($report)) {
            $txt = trim(file_get_contents($report));
        }

        $res[$prefix] = ['report' => $txt, 'summary' => 'ko'];

        if (($goodIfEmpty && $txt == '') || (!empty($goodIfContains) && strpos($txt, $goodIfContains) !== false)) {
            $res[$prefix]['summary'] = 'ok';
        }

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
     *     Lit la date et le temps d'execution de l'analyse
     */
    protected function getAnalyseInfo()
    {
        $file = $this->dirRoot.'/jetons/timeAnalyse';
        if (file_exists($file)) {
            $this->oAnalyze
                ->setDateTime(filemtime($file))
                ->setExecTime((int) file_get_contents($file))
            ;
        }
    }
}
