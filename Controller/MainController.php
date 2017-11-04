<?php

namespace JD\PhpProjectAnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller de l'interface principale
 */
class MainController extends Controller
{
    /**
     * Index
     * @Route("/{_locale]/ppa", requirements={"_locale": "en|fr"})
     * @return Response
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
     * @Route("/ppa/phpinfo")
     * @return Response
     */
    public function phpinfoAction()
    {
        return $this->render('JDPhpProjectAnalyzerBundle:Main:res.html.twig', ['res' => phpinfo()]);
    }

    /**
     * Launch analysis
     * @Route("/ppa/analyze")
     * @return Response
     */
    public function analyzeAction()
    {
        return $this->render(
            'JDPhpProjectAnalyzerBundle:Main:res.html.twig',
            ['res' => $this->get('jd_ppa.scriptManager')->lancerAnalyse()]
        );
    }
}
