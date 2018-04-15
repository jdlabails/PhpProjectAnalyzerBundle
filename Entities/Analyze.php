<?php

namespace JD\PhpProjectAnalyzerBundle\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * La classe Analyze sert à structurer les résultats des analyses
 *
 * @author Jean-David Labails <jd.labails@gmail.com>
 *
 * @ORM\Table(name="ppa_analyse")
 * @ORM\Entity()
 */
class Analyze
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="analyzed_at", type="datetime")
     */
    private $dateTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tu_success", type="boolean", nullable=true, options={"default":false})
     */
    private $tuSuccess;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cs_success", type="boolean", nullable=true, options={"default":false})
     */
    private $csSuccess;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cp_success", type="boolean", nullable=true, options={"default":false})
     */
    private $cpSuccess;

    /**
     * @var boolean
     *
     * @ORM\Column(name="security_success", type="boolean", nullable=true, options={"default":false})
     */
    private $securitySuccess;

    /**
     * @var integer
     *
     * @ORM\Column(name="loc", type="integer", nullable=true)
     */
    private $loc;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var float
     *
     * @ORM\Column(name="cov", type="double(4,2)", nullable=true)
     */
    private $cov;

    /**
     * @var integer
     *
     * @ORM\Column(name="exec_time", type="integer", nullable=true)
     */
    private $execTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_bundles", type="integer", nullable=true)
     */
    private $nbBundles;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_dir", type="integer", nullable=true)
     */
    private $nbDir;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_file", type="integer", nullable=true)
     */
    private $nbFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_php_file", type="integer", nullable=true)
     */
    private $nbPhpFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_css_file", type="integer", nullable=true)
     */
    private $nbCSSFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_css_lib", type="integer", nullable=true)
     */
    private $nbCSSLib;

    /**
     * @var integer
     *
     * @ORM\Column(name="loc", type="integer", nullable=true)
     */
    private $nbJSFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_js_lib", type="integer", nullable=true)
     */
    private $nbJSLib;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_twig", type="integer", nullable=true)
     */
    private $nbTwig;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_namespace", type="integer", nullable=true)
     */
    private $nbNamespace;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_classes", type="integer", nullable=true)
     */
    private $nbClasses;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_method", type="integer", nullable=true)
     */
    private $nbMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", nullable=true)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="symfony_version", type="string", nullable=true)
     */
    private $symfonyVersion;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return analyze
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Retourne une date lisible formaté selon la lang.
     *
     * @return string
     */
    public function getReadableDateTime()
    {
        if ($this->lang == 'fr') {
            return date('d/m/y à H:i', $this->dateTime);
        }

        return date('Y-m-d H:i', $this->dateTime);
    }

    /**
     * Retourne une date formaté js utc
     *
     * @return string
     */
    public function getDateTimeUTC()
    {
        return ($this->dateTime * 1000) - (strtotime('02-01-1970 00:00:00') * 1000);
    }

    /**
     * Set tuSuccess
     *
     * @param boolean $tuSuccess
     * @return analyze
     */
    public function setTuSuccess($tuSuccess)
    {
        $this->tuSuccess = $tuSuccess;

        return $this;
    }

    /**
     * Get tuSuccess
     *
     * @return boolean
     */
    public function getTuSuccess()
    {
        return $this->tuSuccess;
    }

    /**
     * Set csSuccess
     *
     * @param boolean $csSuccess
     * @return analyze
     */
    public function setCsSuccess($csSuccess)
    {
        $this->csSuccess = $csSuccess;

        return $this;
    }

    /**
     * Get csSuccess
     *
     * @return boolean
     */
    public function getCsSuccess()
    {
        return $this->csSuccess;
    }

    /**
     * Set cpSuccess
     *
     * @param boolean $cpSuccess
     * @return analyze
     */
    public function setCpSuccess($cpSuccess)
    {
        $this->cpSuccess = $cpSuccess;

        return $this;
    }

    /**
     * Get cpSuccess
     *
     * @return boolean
     */
    public function getCpSuccess()
    {
        return $this->cpSuccess;
    }

    /**
     * Set securitySuccess
     *
     * @param boolean $securitySuccess
     * @return analyze
     */
    public function setSecuritySuccess($securitySuccess)
    {
        $this->securitySuccess = $securitySuccess;

        return $this;
    }

    /**
     * Get securitySuccess
     *
     * @return boolean
     */
    public function getSecuritySuccess()
    {
        return $this->securitySuccess;
    }

    /**
     * Set loc
     *
     * @param integer $loc
     * @return analyze
     */
    public function setLoc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * Get loc
     *
     * @return integer
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return analyze
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set cov
     *
     * @param float $cov
     * @return analyze
     */
    public function setCov($cov)
    {
        $this->cov = $cov;

        return $this;
    }

    /**
     * Get cov
     *
     * @return float
     */
    public function getCov()
    {
        return $this->cov;
    }

    /**
     * Set execTime
     *
     * @param integer $execTime
     * @return analyze
     */
    public function setExecTime($execTime)
    {
        $this->execTime = $execTime;

        return $this;
    }

    /**
     * Get execTime
     *
     * @return integer
     */
    public function getExecTime()
    {
        return $this->execTime;
    }

    /**
     * Get execTime in a human readable way
     *
     * @return integer
     */
    public function getReadableExecTime()
    {
        if ($this->execTime > 120) {
            return round($this->execTime / 60, 0, PHP_ROUND_HALF_DOWN) . ' min ' . ($this->execTime % 60) . ' sec ';
        }

        return $this->execTime . ' sec';
    }

    /**
     * Set nbBundles
     *
     * @param integer $nbBundles
     * @return analyze
     */
    public function setNbBundles($nbBundles)
    {
        $this->nbBundles = (int)$nbBundles;

        return $this;
    }

    /**
     * Get nbBundles
     *
     * @return integer
     */
    public function getNbBundles()
    {
        return $this->nbBundles;
    }

    /**
     * Set nbDir
     *
     * @param integer $nbDir
     * @return analyze
     */
    public function setNbDir($nbDir)
    {
        $this->nbDir = (int)$nbDir;

        return $this;
    }

    /**
     * Get nbDir
     *
     * @return integer
     */
    public function getNbDir()
    {
        return $this->nbDir;
    }

    /**
     * Set nbFile
     *
     * @param integer $nbFile
     * @return analyze
     */
    public function setNbFile($nbFile)
    {
        $this->nbFile = (int)$nbFile;

        return $this;
    }

    /**
     * Get nbFile
     *
     * @return integer
     */
    public function getNbFile()
    {
        return $this->nbFile;
    }

    /**
     * Set nbPhpFile
     *
     * @param integer $nbPhpFile
     * @return analyze
     */
    public function setNbPhpFile($nbPhpFile)
    {
        $this->nbPhpFile = (int)$nbPhpFile;

        return $this;
    }

    /**
     * Get nbPhpFile
     *
     * @return integer
     */
    public function getNbPhpFile()
    {
        return $this->nbPhpFile;
    }

    /**
     * Set nbCSSFile
     *
     * @param integer $nbCSSFile
     * @return analyze
     */
    public function setNbCSSFile($nbCSSFile)
    {
        $this->nbCSSFile = (int)$nbCSSFile;

        return $this;
    }

    /**
     * Get nbCSSFile
     *
     * @return integer
     */
    public function getNbCSSFile()
    {
        return $this->nbCSSFile;
    }

    /**
     * Set nbCSSLib
     *
     * @param integer $nbCSSLib
     * @return analyze
     */
    public function setNbCSSLib($nbCSSLib)
    {
        $this->nbCSSLib = (int)$nbCSSLib;

        return $this;
    }

    /**
     * Get nbCSSLib
     *
     * @return integer
     */
    public function getNbCSSLib()
    {
        return $this->nbCSSLib;
    }

    /**
     * Set nbJSFile
     *
     * @param integer $nbJSFile
     * @return analyze
     */
    public function setNbJSFile($nbJSFile)
    {
        $this->nbJSFile = (int)$nbJSFile;

        return $this;
    }

    /**
     * Get nbJSFile
     *
     * @return integer
     */
    public function getNbJSFile()
    {
        return $this->nbJSFile;
    }

    /**
     * Set nbJSLib
     *
     * @param integer $nbJSLib
     * @return analyze
     */
    public function setNbJSLib($nbJSLib)
    {
        $this->nbJSLib = (int)$nbJSLib;

        return $this;
    }

    /**
     * Get nbJSLib
     *
     * @return integer
     */
    public function getNbJSLib()
    {
        return $this->nbJSLib;
    }

    /**
     * Set nbTwig
     *
     * @param integer $nbTwig
     * @return analyze
     */
    public function setNbTwig($nbTwig)
    {
        $this->nbTwig = (int)$nbTwig;

        return $this;
    }

    /**
     * Get nbTwig
     *
     * @return integer
     */
    public function getNbTwig()
    {
        return $this->nbTwig;
    }

    /**
     * Set nbNamespace
     *
     * @param integer $nbNamespace
     * @return analyze
     */
    public function setNbNamespace($nbNamespace)
    {
        $this->nbNamespace = (int)$nbNamespace;

        return $this;
    }

    /**
     * Get nbNamespace
     *
     * @return integer
     */
    public function getNbNamespace()
    {
        return $this->nbNamespace;
    }

    /**
     * Set nbClasses
     *
     * @param integer $nbClasses
     * @return analyze
     */
    public function setNbClasses($nbClasses)
    {
        $this->nbClasses = (int)$nbClasses;

        return $this;
    }

    /**
     * Get nbClasses
     *
     * @return integer
     */
    public function getNbClasses()
    {
        return $this->nbClasses;
    }

    /**
     * Set nbMethod
     *
     * @param integer $nbMethod
     * @return analyze
     */
    public function setNbMethod($nbMethod)
    {
        $this->nbMethod = (int)$nbMethod;

        return $this;
    }

    /**
     * Get nbMethod
     *
     * @return integer
     */
    public function getNbMethod()
    {
        return $this->nbMethod;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return analyze
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Serialize this
     * @return type
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * Set an Analyse object from an array
     * @param array $data
     */
    public function setFromArray($data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @return string
     */
    public function getSymfonyVersion()
    {
        return $this->symfonyVersion;
    }

    /**
     * @param string $symfonyVersion
     * @return Analyze
     */
    public function setSymfonyVersion($symfonyVersion)
    {
        $this->symfonyVersion = $symfonyVersion;
        return $this;
    }
}
