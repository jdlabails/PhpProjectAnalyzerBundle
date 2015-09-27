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
        $projectAnalyser = $this->get('jd_ppa.projectAnalyser');

        return $this->render('JDPhpProjectAnalyzerBundle:Main:index.html.twig', [
                'projectAnalyser'       => $projectAnalyser,
                'params'                => $this->getParameter('jd.ppa.global'),
                'tabAvailableAnalysis'  => $projectAnalyser->getTabAvailableAnalysis(),
                'isAnalyzeInProgress'   => $projectAnalyser->isAnalyzeInProgress(),
                '_quality_info'         => $projectAnalyser->getQualityInfo(),
                '_testInfo'             => $projectAnalyser->getTestInfo(),
                '_reportInfo'           => $projectAnalyser->getReportInfo(),
                'analyze'               => $projectAnalyser->getAnalyze(),
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
