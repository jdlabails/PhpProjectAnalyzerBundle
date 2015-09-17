<?php

namespace JD\PhpProjectAnalyzerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
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
                ->scalarNode('lang')->defaultValue('en')->end()
                ->scalarNode('gitRepositoryURL')->defaultNull()->end()
                ->scalarNode('srcPath')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('reportPath')->isRequired()->cannotBeEmpty()->end()

                ->booleanNode('count')->defaultTrue()->end()
                ->booleanNode('cpd')->defaultTrue()->end()
                ->arrayNode('cs')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->scalarNode('standard')->defaultValue('PSR2')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->booleanNode('depend')->defaultTrue()->end()
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
                        ->scalarNode('phpunitTestSuite')->cannotBeEmpty()->end()
                        ->scalarNode('phpunitTestConfig')->cannotBeEmpty()->end()
                    ->end()
                ->end()

                ->arrayNode('score')
                    ->children()
                        ->booleanNode('enable')->defaultTrue()->end()
                        ->scalarNode('csWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('testWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('locWeight')->defaultValue('100')->cannotBeEmpty()->end()
                        ->scalarNode('projectSize')->defaultValue('medium')->cannotBeEmpty()->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}