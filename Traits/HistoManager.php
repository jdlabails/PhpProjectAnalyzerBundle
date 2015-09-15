<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

/*
 * Regroupement des fonctions liées à la fonctionnalité historique du project analyzer
 */
trait HistoManager
{
    /**
     * Retourne les analyses par lecture des json
     * @return array analyze
     */
    public function getAnalyses()
    {
        $res = array();
        $fileName = $this->_dirRoot.'generated/reports/HISTORIQUE/'.date('ym').'.json';
        $string = file_get_contents($fileName);
        $tab = json_decode($string, true);

        foreach ($tab as $t) {
            $a = new analyze();
            $a->setFromArray($t);
            $res []=$a;
        }

        return $res;
    }

    /**
     * Historise l'analyse en .json
     */
    public function historise()
    {
        $fileName = $this->_dirRoot.'generated/reports/HISTORIQUE/'.date('ym').'.json';
        if (file_exists($fileName)) {
            $string = file_get_contents($fileName);
            $tab = json_decode($string, true);
            if ($tab != null && !key_exists($this->oAnalyze->getDateTimeUTC(), $tab)) {
                $tab += array($this->oAnalyze->getDateTimeUTC() => $this->oAnalyze);
            }
        } else {
            $tab = array($this->oAnalyze->getDateTimeUTC() => $this->oAnalyze);
        }

        if (!empty($tab)) {
            $stream = fopen($fileName, 'w+');
            if ($this->getParam('histo', 'jsonPretty') == 'true') {
                fwrite($stream, json_encode($tab, JSON_PRETTY_PRINT));
            } else {
                fwrite($stream, json_encode($tab));
            }
        }
    }

    public function getTabJS4HistoGraph()
    {
        $res = '';
        $dataLoc    = 'dataLoc = [';
        $dataFile   = 'dataFile = [';
        $dataPhp    = 'dataPhp = [';
        $dataJS     = 'dataJS = [';
        $dataCSS    = 'dataCSS = [';
        $dataClasse = 'dataClasse = [';
        $dataCs     = 'dataCs = [';
        $dataTu     = 'dataTu = [';
        $dataNote   = 'dataNote = [';
        $dataCov    = 'dataCov = [';
        $success = 0;
        foreach ($this->getAnalyses() as $i => $a) {
            $dataLoc .= '['.$a->getDateTimeUTC().','.$a->getLoc().'],';
            $dataFile .= '['.$a->getDateTimeUTC().','.$a->getNbFile().'],';
            $dataPhp .= '['.$a->getDateTimeUTC().','.$a->getNbPhpFile().'],';
            $dataJS .= '['.$a->getDateTimeUTC().','.$a->getNbJSFile().'],';
            $dataCSS .= '['.$a->getDateTimeUTC().','.$a->getNbCSSFile().'],';
            $dataClasse .= '['.$a->getDateTimeUTC().','.$a->getNbClasses().'],';
            $dataCs .= '['.$a->getDateTimeUTC().','.$a->getCsSuccess().'],';
            $dataTu .= '['.$a->getDateTimeUTC().','.$a->getTuSuccess().'],';
            $dataNote .= '['.$a->getDateTimeUTC().','.$a->getScore().'],';
            if ($a->getCov() != '/') {
                $dataCov .= '['.$a->getDateTimeUTC().',"'.$a->getCov().'"],';
            }

            $success += $a->getTuSuccess();
        }
        $successPart = 100 * $success / $i;

        $res .= 'successPart = '.$successPart.';';

        $res .= $dataLoc . '];';
        $res .= $dataFile . '];';
        $res .= $dataPhp . '];';
        $res .= $dataJS . '];';
        $res .= $dataCSS . '];';
        $res .= $dataClasse . '];';
        $res .= $dataCs . '];';
        $res .= $dataTu . '];';
        $res .= $dataNote . '];';
        $res .= $dataCov . '];';

        return $res;
    }
}
