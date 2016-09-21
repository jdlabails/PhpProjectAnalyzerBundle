<?php

namespace JD\PhpProjectAnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Initialization command of analysis directories
 */
class InitCommand extends ContainerAwareCommand
{
    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('ppa:init')
            ->setDescription('Init directories for report')
        ;
    }

    /**
     * Execution
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return type
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reflClass = new \ReflectionClass(get_class($this));
        $dirPath = dirname($reflClass->getFileName());

        $installerPath = $dirPath . '/../Resources/sh/install.sh';
        $reportPath = $this->getContainer()->getParameter('kernel.root_dir') . '/../web/ppa';

        if (!is_executable($installerPath)) {
            chmod($installerPath, 0755);
            if (!is_executable($installerPath)) {
                $output->writeln(basename($installerPath) . ' non executable');

                return;
            }
        }

        $question = new Question('Please enter your web server user [www-data:www-data] :', 'www-data:www-data');
        $webServer = $this->getHelperSet()->get('question')->ask($input, $output, $question);

        $outputExec = [];
        exec($installerPath. ' ' . $webServer . ' ' . $reportPath, $outputExec);
        $outputExec[] = "\nInstallation done";
        $output->writeln($outputExec);
    }
}
