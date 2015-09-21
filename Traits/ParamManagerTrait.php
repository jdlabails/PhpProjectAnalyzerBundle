<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

trait ParamManagerTrait
{
    /**
     * Va chercher les param du fichier yml
     * @param string $name cle du param
     * @param string $attr sous cle du param
     * @return string value du param
     */
    public function getParam($name, $attr = '')
    {
        if (isset($this->parameters[$name])) {
            if ($attr != '' && isset($this->parameters[$name][$attr])) {
                return $this->parameters[$name][$attr];
            }

            return $this->parameters[$name];
        }

        return '';
    }

    /**
     * Renvoi vrai si la param est à true dans le yml
     * @param type $paramName
     * @return boolean
     */
    public function isEnable($paramName)
    {
        if (isset($this->parameters[$paramName])) {
            if (is_array($this->parameters[$paramName])) {
                return $this->parameters[$paramName]['enable'];
            }

            return $this->parameters[$paramName];
        }

        return false;
    }
}
