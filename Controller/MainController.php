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

        return $this->render('JDPhpProjectAnalyzerBundle:Main:index.html.twig', [
            'tabAvailableAnalysis'  => $projectAnalyser-> getTabAvailableAnalysis(),
            'projectAnalyser'       => $projectAnalyser,
            'params'                => $this->getParameter('jd.ppa.global'),
            'isAnalyzeInProgress'   => $projectAnalyser->isAnalyzeInProgress(),
            'analyze'               => $projectAnalyser->getAnalyze(),
            '_quality_info'         => $projectAnalyser->getQualityInfo(),
            '_testInfo'             => $testInfo,
            '_reportInfo'           => $projectAnalyser->getReportInfo(),
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
