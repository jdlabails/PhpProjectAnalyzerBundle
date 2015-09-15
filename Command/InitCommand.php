<?php

namespace JD\PhpProjectAnalyzerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class InitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ppa:init')
            ->setDescription('Init directories for report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $param = $this->getContainer()->getParameter('jd.ppa.global');
        $installerPath = __DIR__.'/../Resources/sh/install.sh';

        if (!is_executable($installerPath)) {
            chmod($installerPath, 0755);
            if (!is_executable($installerPath)) {
                $output->writeln(basename($installerPath).' non executable');
                return;
            }
        }
        
        $dialog = $this->getHelperSet()->get('dialog');
        
        $webServer = $dialog->ask(
            $output,
            'Please enter your web server user [www-data:www-data] :',
            'www-data:www-data'
        );
        
        $res = '';
        exec(__DIR__.'/../Resources/sh/install.sh '.$webServer.' '.$param['reportPath'], $res);

        $res []= "\nInstallation done";
        
        $output->writeln($res);
    }
}
