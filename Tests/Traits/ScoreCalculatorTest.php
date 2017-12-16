<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

use JD\PhpProjectAnalyzerBundle\Traits;
use JD\PhpProjectAnalyzerBundle\Entities\Analyze;

/**
 * Tests unitaire du trait Visualizer
 */
class ScoreCalculatorTest extends \PHPUnit_Framework_TestCase
{
    use Traits\ScoreCalculator, Traits\ParamReader;

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

    public function testCalculateScore()
    {
        // resultats de l'analyse
        $this->oAnalyze = new Analyze();
        $this->oAnalyze
            ->setCov(100)
            ->setCpSuccess(false)
            ->setCsSuccess(true)
            ->setSecuritySuccess(false)
            ->setLoc(8564)
            ->setTuSuccess(true)
            ;

        // si le scoring n'est pas enable
        $this->parameters['score']['enable'] = 'false';
        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), 0);

        // scoring enable
        $this->parameters['score'] = [
            'enable' => true,
            'testWeight' => '20',
            'cpWeight' => '30',
            'scWeight' => '40',
            'locWeight' => '50',
            'csWeight' => '60',
        ];

        $note   =
            60 + // cs
            20 + // test
            8564 * 50 / 10000; // loc
        $divide = (20 + 30 + 40 + 50 + 60) / 20;
        $score  = round(($note / $divide), 2);

        $this->calculateScore();
        $this->assertEquals($this->oAnalyze->getScore(), $score);
    }
}
