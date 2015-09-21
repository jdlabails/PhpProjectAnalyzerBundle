<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

use JD\PhpProjectAnalyzerBundle\Traits\ScoreManagerTrait;
use JD\PhpProjectAnalyzerBundle\Traits\ParamManagerTrait;
use JD\PhpProjectAnalyzerBundle\Entities\Analyze;

/**
 * Tests unitaire du trait Visualizer
 */
class ScoreManagerTraitTest extends \PHPUnit_Framework_TestCase
{
    use ScoreManagerTrait, ParamManagerTrait;

    public $parameters;
    public $oAnalyze;

    /**
     * Test la reconnaissance du pramaetrage du score
     */
    public function testIsScoreEnable()
    {
        $this->parameters['score']['enable'] = 'true';
        $this->isTrue($this->isScoreEnable());
    }

    /**
     * Test la lecture des parametres de poids du score
     */
    public function testGetScoreWeightParam()
    {
        // un bon parametrage
        $this->parameters['score']['csWeight'] = 80;
        $this->assertEquals($this->getScoreWeightParam('csWeight'), 80);

        // parametrage hors limite
        $this->parameters['score']['testWeight'] = 8650;
        $this->assertEquals($this->getScoreWeightParam('testWeight'), 100);
        $this->parameters['score']['testWeight'] = -8650;
        $this->assertEquals($this->getScoreWeightParam('testWeight'), 100);

        // parametrage non numerique
        $this->parameters['score']['locWeight'] = 'nimpa';
        $this->assertEquals($this->getScoreWeightParam('locWeight'), 100);
    }

    /**
     * Test du formatage des dates
     */
    public function testCalculateScore()
    {
        // resultats de l'analyse
        $this->oAnalyze = new Analyze();
        $this->oAnalyze
            ->setCov('100%')
            ->setCsSuccess(true)
            ->setLoc(10000)
            ->setTuSuccess(true)
            ;

        // si le scoring n'est pas enable
        $this->parameters['score']['enable'] = 'false';
        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), 0);

        // scoring enable
        $this->parameters['score'] = [
            'enable' => true,
            'testWeight' => '100',
            'locWeight' => '100',
            'csWeight' => '100',
            'projectSize' => 'small',
        ];

        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), 20);

        $this->parameters['score']['projectSize'] = 'medium';
        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), 14.67);

        $this->parameters['score']['projectSize'] = 'big';
        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), 14);
    }
}
