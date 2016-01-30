<?php

namespace JD\PhpProjectAnalyzerBundle\Manager;

use JD\PhpProjectAnalyzerBundle\Traits;

/**
 * scriptManager s'occupe de générer et de lancer le script d'analyse
 *
 * @author jd.labails
 */
class ScriptManager
{
    use Traits\ScriptBuilder, Traits\ParamReader;

    private $dirRoot;
    private $parameters;
    private $jetonAnalysePath;
    private $paShPath;
    private $tplShDirPath;

    /**
     * Le constructeur initialise les variables necessaire à la génération du script
     *
     * @param type $configGlobale
     */
    public function __construct($configGlobale, $rootDir)
    {
        $this->dirRoot             = $rootDir.'/web/ppa/';
        $this->jetonAnalysePath    = $this->dirRoot.'/jetons/jetonAnalyse';
        $this->shDirPath           = $this->dirRoot.'/sh';

        $reflClass                 = new \ReflectionClass(get_class($this));
        $this->tplShDirPath        = dirname($reflClass->getFileName()).'/../Resources/sh';

        $this->parameters          = $configGlobale;
    }

    /**
     * Lance le shell d'analyse en tache de fond
     *
     * @return string état lisible de l'analyse
     */
    public function lancerAnalyse()
    {
        // si une analyse est en cours on dégage
        if (file_exists($this->jetonAnalysePath)) {
            return 'Analyse en cours';
        }

        // si on demande ou on en est et qu'on s'est pas encore fait degagé alors c fini
        if (filter_input(INPUT_GET, 'statut') == 1) {
            return 'ok';
        }

        // si on arrive là on doit lancer l'analyse selon la config
        $this->paShPath = $this->shDirPath.'/pa.sh';
        $this->creerAnalyses();

        // lancement unitaire : le sh à lancer n'est pas la meme
        if (filter_input(INPUT_POST, 'one') != '') {
            $this->paShPath = $this->dirRoot.'/sh/one/'.filter_input(INPUT_POST, 'one').'.sh';
        }

        // on vérifie qu'on peut executer le sh
        if (!is_executable($this->paShPath)) {
            chmod($this->paShPath, 0777);
            if (!is_executable($this->paShPath)) {
                return basename($this->paShPath).' non executable';
            }
        }

        // on init le text de feedback
        $txt = 'Analyse ';

        // on init la commande
        $cmd = $this->paShPath;

        // on gere les options
        if (filter_input(INPUT_POST, 'genDoc') == 1) {
            $cmd .= ' -d ';
            $txt .= ' avec génération de doc ';
        }

        if (filter_input(INPUT_POST, 'genCC') == 1) {
            $cmd .= ' -c ';
            $txt .= 'avec code coverage';
        }

        // on lance l'analyse, c'est à dire le sh
        exec('nohup '.$cmd.' > '.$this->dirRoot.'/jetons/output.log 2> '.$this->dirRoot.'/jetons/error.log &');

        return $txt.' lancée ('.$cmd.')';
    }
}
