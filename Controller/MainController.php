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
        $projectAnalyzer = $this->get('jd_ppa.projectAnalyzer');

        return $this->render('JDPhpProjectAnalyzerBundle:Main:index.html.twig', [
                'projectAnalyzer' => $projectAnalyzer,
                'params'          => $this->getParameter('jd.ppa.global'),
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
        return $this->render(
            'JDPhpProjectAnalyzerBundle:Main:res.html.twig',
            ['res' => $this->get('jd_ppa.scriptManager')->lancerAnalyse()]
        );
    }
}
