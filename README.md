Php Project Analyzer [![Build Status](https://travis-ci.org/jdlabails/PhpProjectAnalyzer.svg?branch=master)](https://travis-ci.org/jdlabails/PhpProjectAnalyzer)

This bundle gives you constructed views of various analysis results.


It give a view like :

![](https://raw.githubusercontent.com/jdlabails/PhpProjectAnalyzer/master/ppaIndex.png)


### Features
 - Aggregate php analysis metrics
 - Offer user-friendly interface
 - Execute quick scan of your project
 - English or French interfaces
 - Links with code coverage report
 - Scoring based on quantity and quality metrics
 - Enable PhpUnit or Atoum unit tests


It executes
 - Php Mess Detector
 - Php Unit Test
 - Atoum test
 - Php Code Sniffer ( + reparation tool)
 - Copy-paste detector
 - Php Depend
 - Php Loc
 - Php Docs

And parses their report to give a nice view for rapid analysis of your project.

It make sense essentially for dev and lead dev.


### Install
 - composer require jdlabails/php-project-analyzer-bundle
 - php app/console assets:install
 - php app/console assetic:dump
 - Set your config (see below)
 - sudo php app/console ppa:init

### Use
 - Call http://yoursymfonyproject.local/en/ppa with your nav.
 - Click on 'Start Scan'

### Config

```yml
assetic:
    bundles:        
        - JDPhpProjectAnalyzerBundle

jd_php_project_analyzer:
    title:          Php project analyzer
    description:    It's a ouaaaouhh project !
    lang :          en

    gitRepositoryURL:      https://github.com/jdlabails/PhpProjectAnalyzerBundle

    # chemin d'analyse
    srcPath : /home/jd/Dev/ppa/src/JD

    # métrique quantitative
    count : true

    # métrique qualité copy-paste
    cpd : true

    # métrique qualité code sniffer
    cs :
        enable: true
        standard: PSR2

    # métrique qualité php depend
    depend : true

    # métrique qualité php loc
    loc : true

    # métrique qualité mess detector
    md :
        enable: true
        rules:
            cleancode: true
            codesize: true
            controversial: true
            design: true
            naming: true
            unusedcode: true

    # possiblité de généré la phpdoc
    docs : true

    # tests unitaires et fonctionnels
    test :
        enable: false
        lib : phpunit       # phpunit || atoum
        phpunitTestSuite : ppa
#        atoumPath : /home/smith/www/projectX/vendor/bin/atoum
#        atoumTestDir : /absolute/path/to/your/test/dir

    # score
    score:
        enable:         true
        csWeight:       100     # between 0 and 100, weighting of code sniffer
        testWeight:     100     # between 0 and 100, weighting of testing
        locWeight:      100     # between 0 and 100, weighting of code coverage
        projectSize:    small   # small : betwenn 0 and 5000, medium, between 5000 and 50000, big : > 50000

```