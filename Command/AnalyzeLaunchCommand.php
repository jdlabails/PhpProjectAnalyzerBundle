<?php

namespace JD\PhpProjectAnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Initialization command of analysis directories
 */
class AnalyzeLaunchCommand extends ContainerAwareCommand
{
    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('ppa:analyse:launch')
            ->setDescription('Launch the analysis')
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
        $res = $this->getContainer()->get('jd_ppa.scriptManager')->lancerAnalyse();

        $output->writeln($res);
    }
}
