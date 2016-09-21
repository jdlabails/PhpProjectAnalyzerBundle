<?php

namespace JD\PhpProjectAnalyzerBundle\Traits;

/**
 * Description of scriptBuilder
 *
 * @author jd
 */
trait ScriptBuilder
{

    private $header = '';

    private $command = '';

    /**
     * On creer le pa.sh selon les param
     */
    public function creerAnalyses()
    {
        $contentGlobalSh = $contentCbfSh = $this->getHeader();

        foreach ($this->parameters as $idAnalyse => $param) {
            if ($this->isEnable($idAnalyse) && file_exists($this->tplShDirPath . '/' . $idAnalyse . '.tpl.sh')) {
                $contentSh = '';
                switch ($idAnalyse) {
                    case 'md':
                        $contentSh = file_get_contents($this->tplShDirPath . '/md.tpl.sh');
                        $contentSh = str_replace('%%%rule_set%%%', $this->getMDRuleSet(), $contentSh);
                        break;
                    case 'test':
                        if ($param['lib'] == 'phpunit') {
                            $contentSh = file_get_contents($this->tplShDirPath . '/testPhpUnit.tpl.sh');
                            $contentSh = str_replace('%%%testsuite%%%', $param['phpunitTestSuite'], $contentSh);
                            $contentSh = str_replace('%%%testconfig%%%', $param['phpunitTestConfig'], $contentSh);
                        }
                        if ($param['lib'] == 'atoum') {
                            $contentSh = file_get_contents($this->tplShDirPath . '/testAtoum.tpl.sh');
                            $contentSh = str_replace('%%%pathAtoum%%%', $param['atoumPath'], $contentSh);
                            $contentSh = str_replace('%%%dirTestAtoum%%%', $param['atoumTestDir'], $contentSh);
                        }
                        break;
                    case 'cs':
                        $contentSh = file_get_contents($this->tplShDirPath . '/cs.tpl.sh');
                        $cbfContent = file_get_contents($this->tplShDirPath . '/cbf.tpl.sh');
                        $std = 'PSR2';
                        if (strpos($param['standard'], 'PSR') !== null &&
                            strlen($param['standard']) < 10
                        ) {
                            $std = $this->parameters['cs']['standard'];
                        }

                        $cbfContent = str_replace('%%%standard%%%', $std, $cbfContent);
                        $contentSh = str_replace('%%%standard%%%', $std, $contentSh);

                        $contentCbfSh .= $cbfContent;
                        $contentCbfSh .= file_get_contents($this->tplShDirPath . '/footer.tpl.sh');
                        file_put_contents($this->shDirPath . '/one/cbf.sh', $contentCbfSh);
                        break;
                    default:
                        $contentSh = file_get_contents($this->tplShDirPath . '/' . $idAnalyse . '.tpl.sh');
                        break;
                }

                $contentGlobalSh .= $contentSh;

                $content = $this->getHeader();
                $content .= $contentSh;
                $content .= file_get_contents($this->tplShDirPath . '/footer.tpl.sh');

                $this->command = $content;
                file_put_contents($this->shDirPath . '/one/' . $idAnalyse . '.sh', $content);
            }
        }

        $contentGlobalSh .= file_get_contents($this->tplShDirPath . '/footer.tpl.sh');
        file_put_contents($this->paShPath, $contentGlobalSh);
    }

    private function getMDRuleSet()
    {
        $availableRule = ['cleancode', 'codesize', 'controversial', 'design', 'naming', 'unusedcode'];
        $tabRule = [];

        foreach ($availableRule as $r) {
            if ($this->parameters['md']['rules'][$r] == 'true') {
                $tabRule[] = $r;
            }
        }

        return implode(',', $tabRule);
    }

    private function getHeader()
    {
        if ($this->header == '') {
            $this->header = file_get_contents($this->tplShDirPath . '/header.tpl.sh');
            $this->header = str_replace('%%%dir_src%%%', $this->parameters['srcPath'], $this->header);
            $this->header = str_replace('%%%dir_pa%%%', $this->dirRoot, $this->header);
            $this->header = str_replace('%%%dir_phar%%%', __DIR__ . '/../Resources/_phar', $this->header);
            $this->header = str_replace('%%%lock_path%%%', $this->dirRoot . '/../../composer.lock', $this->header);
        }

        return $this->header;
    }
}
