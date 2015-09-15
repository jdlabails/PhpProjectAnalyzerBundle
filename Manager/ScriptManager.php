<?php

namespace JD\PhpProjectAnalyzerBundle\Manager;

use JD\PhpProjectAnalyzerBundle\Traits\ParamManager;
use JD\PhpProjectAnalyzerBundle\Traits\ScriptBuilder;

/**
 * scriptManager s'occupe de générer et de lancer le script d'analyse
 *
 * @author jd.labails
 */
class ScriptManager
{
    private $_dirRoot;
    private $_parameters;
    private $_mustGenerate;
    private $_jetonAnalysePath;
    private $_paShPath;
    //private $_paramPath;
    private $_tplShDirPath;
    private $_phpDirPath;

    use ScriptBuilder, ParamManager;

    function __construct($configGlobale)
    {
        $this->_dirRoot             = $configGlobale['reportPath'].'/';
        //$this->_paramPath           = $this->_dirRoot.'core/param.yml';
        $this->_jetonAnalysePath    = $this->_dirRoot.'/jetons/jetonAnalyse';
        $this->_shDirPath            = $this->_dirRoot.'/sh';
        $this->_tplShDirPath        = __DIR__.'/../Resources/sh';
        $this->_phpDirPath          = $this->_dirRoot.'/php';

        $this->_parameters          = $configGlobale;

        $this->_mustGenerate        = true;//!file_exists($this->_paShPath) || filemtime($this->_paShPath) < filemtime($this->_paramPath);
    }

    public function getState()
    {
        
    }
    
    function lancerAnalyse()
    {
        // si une analyse est en cours on dégage
        if (file_exists($this->_jetonAnalysePath)) {
            return 'Analyse en cours';
        }

        // si on demande ou on en est et qu'on s'est pas encore fait degagé alors c fini
        if (filter_input(INPUT_GET, 'statut') == 1) {
            return 'ok';
        }

        // si on arrive là on doit lancer l'analyse selon la config
        if ($this->_mustGenerate) {
            $this->creerAnalyses();
        }

        $this->_paShPath = $this->_shDirPath.'/pa.sh';
        
        // lancement unitaire : le sh à lancer n'est pas la meme
        if (filter_input(INPUT_POST, 'one') != '') {
            $this->_paShPath = $this->_dirRoot.'/sh/one/'.filter_input(INPUT_POST, 'one').'.sh';
        }

        // on vérifie qu'on peut executer le sh
        if (!is_executable($this->_paShPath)) {
            chmod($this->_paShPath, 0777);
            if (!is_executable($this->_paShPath)) {
                return basename($this->_paShPath).' non executable';
            }
        }

        // on init le text de feedback
        $txt = 'Analyse ';

        // on init la commande
        $cmd = $this->_paShPath;

        // on gere les options
        if (filter_input(INPUT_POST, 'genDoc') == 1) {
            $cmd.=' -d ';
            $txt .= ' avec génération de doc ';
        }

        if (filter_input(INPUT_POST, 'genCC') == 1) {
            $cmd.=' -c ';
            $txt .= 'avec code coverage';
        }

        // on lance l'analyse, c'est à dire le sh
        exec('nohup '.$cmd. ' > '.$this->_dirRoot.'/jetons/output.log 2> '.$this->_dirRoot.'/jetons/error.log &');

        return $txt.' lancée ('.$cmd.')';
    }
}
