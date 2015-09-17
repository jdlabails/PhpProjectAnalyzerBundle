<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

use JD\PhpProjectAnalyzerBundle\Traits\VisualizerTrait;

/**
 * Tests unitaire du trait Visualizer
 */
class VisualizerTraitTest extends \PHPUnit_Framework_TestCase
{
    use VisualizerTrait;

    public $parameters;

    /**
     * Test de l'affichage des résumés html
     */
    public function testAfficheSummary()
    {
        $this->assertEquals(self::afficheSummary('ok'), '<span class="badge alert-success value">OK</span>');
        $this->assertEquals(self::afficheSummary('ko'), '<span class="badge alert-warning value">KO</span>');
        $this->assertEquals(self::afficheSummary('nimp'), '<span class="badge alert-warning value">NC</span>');
    }

    /**
     * Test de la transformation htmk des rapports générés
     */
    public function testAdaptPhpUnitReport()
    {
        $file = __DIR__.'/../Fixtures/Traits/visualizer.txt';
        $this->assertEquals(self::adaptPhpUnitReport($file), '<span style="color:green"><span style="color:red"><span style=""><span style=""></span>');
    }

    /**
     * Test du formatage des dates
     */
    public function testGetReadableDateTime()
    {
        $date = strtotime('10 sept 2015 12:00');

        $this->parameters['lang'] = 'fr';
        $this->assertEquals(self::getReadableDateTime($date), '10/09/2015 à 12:00');

        $this->parameters['lang'] = 'en';
        $this->assertEquals(self::getReadableDateTime($date), '2015-09-10 12:00');
    }
}
