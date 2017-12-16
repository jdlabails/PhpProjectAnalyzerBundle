<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

use JD\PhpProjectAnalyzerBundle\Traits;
use Symfony\Component\Translation\Translator;

/**
 * Tests unitaire du trait Visualizer
 */
class ViewHelperTest extends \PHPUnit_Framework_TestCase
{
    use Traits\ViewHelper;

    public $parameters;

    /**
     * Test de l'affichage des résumés html
     */
    public function testViewSummary()
    {
        $this->assertEquals(self::viewSummary('ok'), '<span class="badge alert-success value">OK</span>');
        $this->assertEquals(self::viewSummary('ko'), '<span class="badge alert-warning value">KO</span>');
        $this->assertEquals(self::viewSummary('nimp'), '<span class="badge alert-warning value">NC</span>');
    }

    /**
     * Test de la transformation htmk des rapports générés
     */
    public function testAdaptPhpUnitReport()
    {
        $reflClass = new \ReflectionClass(get_class($this));
        $file      = dirname($reflClass->getFileName()).'/../Fixtures/Traits/visualizer.txt';
        $this->assertEquals(self::adaptPhpUnitReport($file), '<span style="color:green"><span style="color:red"><span style=""><span style=""></span>');
    }

    /**
     * Test du formatage des dates
     */
    public function testGetReadableDateTime()
    {
        $this->translator = $this
            ->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $date = strtotime('10 sept 2015 12:00');

        $this->translator->expects($this->any())
             ->method('getLocale')
             ->will($this->returnValue('fr'));
        $this->assertEquals($this->getReadableDateTime($date), '10/09/2015 à 12:00');

        $this->translator = $this
            ->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->translator->expects($this->any())
             ->method('getLocale')
             ->will($this->returnValue('en'));
        $this->assertEquals($this->getReadableDateTime($date), '2015-09-10 12:00');
    }
}
