<?php

namespace JD\PhpProjectAnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller de l'interface principale
 */
class MainController extends Controller
{
    /**
     * Index
     * @return type
     */
    public function indexAction()
    {
        $projectAnalyser    = $this->get('jd_ppa.projectAnalyser');
        $testInfo          = $projectAnalyser->exploitTestReport();

        $tabAvailableAnalysis = [
            'test'      => 'Tests fonctionnels et unitaires',
            'md'        => 'PhpMD : Mess Detector',
            'cpd'       => 'CPD : Copy-Paste Detector',
            'cs'        => 'CS : Code Sniffer',
            'loc'       => 'PhpLoc : Statistic',
            'docs'      => 'PhpDoc : Documentation',
            'depend'    => 'PhpDepend : Métriques d\'analyse',
        ];

        return $this->render('JDPhpProjectAnalyzerBundle:Main:index.html.twig', [
            'tabAvailableAnalysis'  => $tabAvailableAnalysis,
            'projectAnalyser'       => $projectAnalyser,
            'params'                => $this->getParameter('jd.ppa.global'),
            'isAnalyzeInProgress'   => $projectAnalyser->isAnalyzeInProgress(),
            'a'                     => $projectAnalyser->getAnalyze(),
            '_quality_info'         => $projectAnalyser->getQualityInfo(),
            '_testInfo'             => $testInfo,
            '_reportInfo'           => $projectAnalyser->getReportInfo(),
            '_note'                 => $projectAnalyser->getNote($testInfo),
        ]);
    }

    /**
     * Display phpinfo
     * @return type
     */
    public function phpinfoAction()
    {
        return $this->render('JDPhpProjectAnalyzerBundle:Main:res.html.twig', ['res' => phpinfo()]);
    }

    /**
     * Launch analysis
     * @return type
     */
    public function analyzeAction()
    {
        $scriptManager = $this->get('jd_ppa.scriptManager');

        return $this->render('JDPhpProjectAnalyzerBundle:Main:res.html.twig', [
            'res' => $scriptManager->lancerAnalyse(),
        ]);
    }
}
