### What is it ?
This bundle gives you a constructed view of various analysis results.


It executes
 - Php Mess Detector
 - Php Unit Test
 - Php Code Sniffer
 - Copy-paste detector
 - Php Depend
 - Php Loc
 - Php Doc

And give a view like :

![](https://raw.githubusercontent.com/jdlabails/PhpProjectAnalyzer/master/ppaIndex.png)



### Install
 - composer require...

### Config

```yml

jd_php_project_analyzer:
    title:          Php project analyzer
    description:    It's a ouaaaouhh project !
    lang :          en

    gitlabURL:      https://github.com/jdlabails/PhpProjectAnalyzerBundle

    # chemin d'analyse
    srcPath : /home/jd/Dev/starterkit_sf2/src/JD
    
    # chemin des rapports
    reportPath : /home/jd/Dev/starterkit_sf2/web/ppaReports

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
        phpunitTestSuite : Annuaire
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

### Init

 - sudo php app/console ppa:init

### Use
 - Call http://yoursymfonyproject.local/ppa with your nav.
 - Click on 'Start Scan'


### Features
 - Aggregate php analysis metrics
 - Execute quick scan of your project
 - English or French interfaces
 - Links with code coverage report
 - Scoring based on quantity and quality metrics
 - Enable PhpUnit or Atoum unit tests
 - Give a score to your project with parametrable weight

