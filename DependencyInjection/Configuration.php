<?php

namespace JD\PhpProjectAnalyzerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration of PPA
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jd_php_project_analyzer');

        $rootNode
            ->children()
                ->scalarNode('title')->defaultValue('Your project')->end()
                ->scalarNode('description')->defaultNull()->end()
                ->scalarNode('gitRepositoryURL')->defaultNull()->end()
                ->scalarNode('srcPath')->isRequired()->cannotBeEmpty()->end()

                ->booleanNode('count')->defaultTrue()->end()
                ->booleanNode('cpd')->defaultTrue()->end()
                ->arrayNode('cs')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->scalarNode('standard')->defaultValue('PSR2')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->booleanNode('depend')->defaultTrue()->end()
                ->booleanNode('security')->defaultTrue()->end()
                ->booleanNode('loc')->defaultTrue()->end()

                ->arrayNode('md')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->arrayNode('rules')
                            ->children()
                                ->booleanNode('cleancode')->defaultTrue()->end()
                                ->booleanNode('codesize')->defaultTrue()->end()
                                ->booleanNode('controversial')->defaultTrue()->end()
                                ->booleanNode('design')->defaultTrue()->end()
                                ->booleanNode('naming')->defaultTrue()->end()
                                ->booleanNode('unusedcode')->defaultTrue()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->booleanNode('docs')->defaultTrue()->end()
                ->arrayNode('test')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->scalarNode('lib')->defaultValue('phpunit')->cannotBeEmpty()->end()
                        ->scalarNode('phpunitTestSuite')->end()
                        ->scalarNode('phpunitTestConfig')->end()
                        ->scalarNode('atoumPath')->end()
                        ->scalarNode('atoumTestDir')->end()
                    ->end()
                ->end()

                ->arrayNode('score')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->scalarNode('csWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('cpWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('scWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('testWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('locWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('projectSize')->defaultValue('medium')->cannotBeEmpty()->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
