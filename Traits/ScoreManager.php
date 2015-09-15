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
trait ScoreManager
{
    public function isScoreEnable()
    {
        return $this->getParam('score', 'enable') == 'true';
    }

    /**
     * 20/20 serait donné à un projet de 100kLoc tester à 100% avec CS ok
     * @param type $t_info
     * @todo les pondérations pourraient être en param.yml, notament celle sur loc
     * @return type
     */
    function getNote($t_info)
    {
        if (! $this->isScoreEnable()) {
            return 0;
        }

        $loc    = $this->extractFromLoc('loc');
        $this->oAnalyze->setLoc((int)$loc);

        $cs     = (int)($this->oAnalyze->getCsSuccess() === true);
        $test   = (int)$t_info['ok'];
        $cc     = (float)str_replace('%', '', $t_info['ccLine']);

        $csWeight       = $this->getScoreWeightParam('csWeight');
        $testWeight     = $this->getScoreWeightParam('testWeight');
        $locWeight      = $this->getScoreWeightParam('locWeight');

        $projectSize    = $this->getParam('score', 'projectSize');
        $maxSize = 50000;
        switch ($projectSize) {
            case 'small':
                $maxSize = 10000;
                break;
            default:
            case 'medium':
                $maxSize = 50000;
                break;
            case 'big':
                $maxSize = 100000;
                break;
        }

        //echo "$cs*$csWeight + $test*$testWeight*($cc/100) + $loc*$locWeight/$maxSize;";
        $note = $cs*$csWeight + $test*$testWeight*($cc/100) + $loc*$locWeight/$maxSize;
        $divide = ($csWeight + $testWeight + $locWeight) / 20;

        $score = round(($note/$divide), 2);

        $this->oAnalyze->setScore($score);

        return $score;
    }

    function getScoreWeightParam($name)
    {
        $weight = $this->getParam('score', $name);
        if (! is_int($weight)) {
            return 100;
        }

        if ($weight < 0 || $weight > 100) {
            return 100;
        }

        return $weight;
    }
}
