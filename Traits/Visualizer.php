<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

/**
 * Methodes de visualisation
 *
 * Utilisable uniquement dans la classe projectAnalyzer
 * car les this s'y referent
 *
 * @author jd.labails
 */
trait Visualizer
{
    function afficheSummary($summary)
    {
        switch ($summary) {
            case 'ok':
                $txt = '<span class="badge alert-success value">OK</span>';
                break;
            case 'ko':
                $txt = '<span class="badge alert-warning value">KO</span>';
                break;
            default:
                $txt = '<span class="badge alert-warning value">NC</span>';
                break;
        }

        return $txt;
    }

    /**
     * Gestion de la trad
     * @param string $label
     * @return string
     */
    public function getLabel($label)
    {
        return key_exists($label, $this->labels) ? $this->labels[$label] : $label;
    }

    /**
     * Adapte le rapport phpunit pour mettre en vert le res
     * @param type $file
     * @return type
     */
    function adaptPhpUnitReport($file)
    {
        $txt = '<br>'.file_get_contents($file);
        $txt = str_replace('[30;42m', '<span style="color:green">', $txt);
        $txt = str_replace('[37;41m', '<span style="color:red">', $txt);
        $txt = str_replace('[31;1m', '<span style="">', $txt);
        $txt = str_replace('[41;37m', '<span style="">', $txt);
        $txt = str_replace('[0m', '</span>', $txt);

        return $txt;
    }

    /**
     * Retourne une date lisible formaté selon la langue
     * @param datetime $dt
     * @return string
     */
    function getReadableDateTime($dt)
    {
        if ($this->parameters['lang'] == 'fr') {
            return date('d/m/y à H:i', $dt);
        } else {
            return date('Y-m-d H:i', $dt);
        }
    }
}
