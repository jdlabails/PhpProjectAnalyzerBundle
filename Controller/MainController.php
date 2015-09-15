<?php

namespace JD\PhpProjectAnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        //var_dump($this->getParameter('jd.ppa.global'));
        //$this->get('jd_php_project_analyzer')->get('title');

        $projectAnalyser    = $this->get('jd_ppa.projectAnalyser');
        $_testInfo          = $projectAnalyser->exploitTestReport();

        $tabAvailableAnalysis = array(
            'test'      => 'Tests fonctionnels et unitaires',
            'md'        => 'PhpMD : Mess Detector',
            'cpd'       => 'CPD : Copy-Paste Detector',
            'cs'        => 'CS : Code Sniffer',
            'loc'       => 'PhpLoc : Statistic',
            'docs'      => 'PhpDoc : Documentation',
            'depend'    => 'PhpDepend : Métriques d\'analyse'
        );

        return $this->render('JDPhpProjectAnalyzerBundle:Main:index.html.twig', [
            'tabAvailableAnalysis'  => $tabAvailableAnalysis,
            'projectAnalyser'       => $projectAnalyser,
            'params'                => $this->getParameter('jd.ppa.global'),
            'isAnalyzeInProgress'   => $projectAnalyser->isAnalyzeInProgress(),
            'a'                     => $projectAnalyser->getAnalyze(),
            '_quality_info'         => $projectAnalyser->getQualityInfo(),
            '_testInfo'             => $_testInfo,
            '_reportInfo'           => $projectAnalyser->getReportInfo(),
            '_note'                 => $projectAnalyser->getNote($_testInfo)
        ]);
    }

    public function phpinfoAction()
    {
        return $this->render('JDPhpProjectAnalyzerBundle:Main:res.html.twig', ['res' => phpinfo()]);
    }

    public function analyzeAction()
    {
        $scriptManager = $this->get('jd_ppa.scriptManager');

        return $this->render('JDPhpProjectAnalyzerBundle:Main:res.html.twig', [
            'res' => $scriptManager->lancerAnalyse()
        ]);
    }
}
