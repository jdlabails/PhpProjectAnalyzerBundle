<?php

namespace JD\PhpProjectAnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Initialization command of analysis directories
 */
class AnalyzeReadCommand extends ContainerAwareCommand
{
    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('ppa:analyse:read')
            ->setDescription('Read the last analysis')
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
        $projectAnalyzer = $this->getContainer()->get('jd_ppa.projectAnalyzer');

        if ($projectAnalyzer->isAnalyzeInProgress()) {
            $output->writeln('AIP');
            return;
        }

        $output->writeln(json_encode($projectAnalyzer->getAnalyze()->jsonSerialize()));
    }
}
