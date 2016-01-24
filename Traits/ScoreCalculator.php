<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

/**
 * Regroupent les fonctions pour la scoring machine
 *
 * Utilisable uniquement dans la classe projectAnalyzer
 * car les this s'y referent
 *
 * @author jd.labails
 */
trait ScoreCalculator
{

    /**
     * Return true if score is enable by the config file
     * @return type
     */
    public function isScoreEnable()
    {
        return $this->getParam('score', 'enable') == 'true';
    }

    /**
     * Calcule le score en fonction des analyses et du parametrage, puis set l'objet analyse
     *
     * 20/20 serait donné à un projet de 100kLoc tester à 100% avec CS ok
     */
    protected function calculateScore()
    {
        if (!$this->isScoreEnable()) {
            return 0;
        }

        $loc = (int) $this->oAnalyze->getLoc();
        $cs = (int) ($this->oAnalyze->getCsSuccess() === true);
        $test = (int) ($this->oAnalyze->getTuSuccess() === true);
        $cc = (float) $this->oAnalyze->getCov(); //str_replace('%', '', $testInfo['ccLine']);

        $csWeight = $this->getScoreWeightParam('csWeight');
        $testWeight = $this->getScoreWeightParam('testWeight');
        $locWeight = $this->getScoreWeightParam('locWeight');

        // define size category of the project
        $maxSize = 10000; // small, default
        if ($loc >= 10000) {
            // medium
            $maxSize = 50000;
        }
        if ($loc >= 50000) {
            // big
            $maxSize = 100000;
        }

        //echo "$cs*$csWeight + $test*$testWeight*($cc/100) + $loc*$locWeight/$maxSize;";
        $note = $cs * $csWeight + $test * $testWeight * ($cc / 100) + $loc * $locWeight / $maxSize;
        $divide = ($csWeight + $testWeight + $locWeight) / 20;
        $score = round(($note / $divide), 2);

        $this->oAnalyze->setScore($score);
    }

    /**
     * Return weight of the given parameter
     * @param type $name
     * @return int
     */
    public function getScoreWeightParam($name)
    {
        $weight = $this->getParam('score', $name);
        if (!is_int($weight)) {
            return 100;
        }

        if ($weight < 0 || $weight > 100) {
            return 100;
        }

        return $weight;
    }
}
