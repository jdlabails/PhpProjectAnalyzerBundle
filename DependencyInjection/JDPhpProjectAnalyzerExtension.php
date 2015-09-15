<?php

namespace JD\PhpProjectAnalyzerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JDPhpProjectAnalyzerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $this->addConfigToParameter('jd.ppa', $config, $container);
        $container->setParameter('jd.ppa.global', $config);
    }
    
    private function addConfigToParameter($prefix, array $config, ContainerBuilder $container)
    {
        foreach ($config as $k => $v) {
            $paramName = $prefix.'.'.$k;
            if (!is_array($v)) {
                //echo $paramName.' => '.$v.'<br>';
                $container->setParameter($paramName, $v);
            } else {
                $this->addConfigToParameter($paramName, $v, $container);
            }
        }
    }
}
