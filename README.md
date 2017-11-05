# Php Project Analyzer Bundle

Gives you consolidated views of analysis results.


[![Build Status](https://travis-ci.org/jdlabails/PhpProjectAnalyzerBundle.svg?branch=master)](https://travis-ci.org/jdlabails/PhpProjectAnalyzerBundle)
[![Total Downloads](https://poser.pugx.org/jdlabails/php-project-analyzer-bundle/d/total.png)](https://packagist.org/packages/jdlabails/php-project-analyzer-bundle)
[![Latest Stable Version](https://poser.pugx.org/jdlabails/php-project-analyzer-bundle/v/stable.png)](https://packagist.org/packages/jdlabails/php-project-analyzer-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3b03dad9-01a6-4d9e-8cb5-72a2fc8190dc/mini.png)](https://insight.sensiolabs.com/projects/3b03dad9-01a6-4d9e-8cb5-72a2fc8190dc)


It give a view like :

![](https://raw.githubusercontent.com/jdlabails/PhpProjectAnalyzerBundle/master/ppaIndex.png)


### Features
 - Aggregate php analysis metrics
 - Offer user-friendly interface
 - Execute quick scan of your project
 - English or French interfaces
 - Links with code coverage report
 - Scoring based on quantity and quality metrics
 - Enable PhpUnit or Atoum unit tests
 - Security checker available


It executes
 - Php Mess Detector
 - Php Unit Test
 - Atoum test
 - Php Code Sniffer ( + reparation tool via phpcbf)
 - Copy-paste detector
 - Php Depend
 - Php Loc

And parses their report to give a nice view for rapid analysis of your project.

### Install
 - composer require jdlabails/php-project-analyzer-bundle --dev
 - add bundle to kernel
```php
/* app/AppKernel.php */
public function registerBundles()
    {
// ...
        $bundles[] = new JD\PhpProjectAnalyzerBundle\JDPhpProjectAnalyzerBundle();
// ...
}
```
 - add routing
```yaml
# app/config/routing.yml
ppa:
    resource: '@JDPhpProjectAnalyzerBundle/Resources/config/routing.yml'
```
 - add security exception
 ```yaml
 access_control:
     # PPA
     - { path: "^/ppa/[a-z]*", roles: IS_AUTHENTICATED_ANONYMOUSLY }
 ```
 
 - Set your config
 
```yml
framework:
    translator: { fallback: %locale% }

jd_php_project_analyzer:
    title:          Php project analyzer
    description:    It's a ouaaaouhh project !

    gitRepositoryURL:      https://github.com/jdlabails/PhpProjectAnalyzerBundle

    # directory to analyze
    srcPath : /home/jd/Dev/ppa/src/JD

    # quantitative metric
    count : true

    # quality metric : copy-paste
    cpd : true

    # quality metric : code sniffer
    cs :
        enable: true
        standard: PSR2

    # security checker
    security: true

    # quality metric : phpdepend
    depend : true

    # quality metric : phploc
    loc : true

    # quality metric : mess detector
    md :
        enable: true
        rules:
            cleancode: true
            codesize: true
            controversial: true
            design: true
            naming: true
            unusedcode: true

    # generate phpdoc
    docs : true

    # testing
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

```


 - set assets
```bash
php app/console assets:install
```

 - set right for ppa directory in the web directory
 ```bash
 sudo php app/console ppa:init
```

### Use
 - Call http://127.0.0.1:8000/en/ppa with your nav.
 - Click on 'Start Scan'


### update your phar

```bash
cd Resources/_phar
chmod +x  update.sh
./update.sh
```

### Need contributions

Examples :
 * avoid phar files for dependencies symfony
 * refacto code
 * unit tests
 * download security checker at each scan
 
Just make a pull request on master
 
##### Check style
```bash
bin/phpcs --standard=PSR2 --extensions=php Entities Manager Command Controller DependencyInjection Traits
```
 
##### Unit Tests
```bash
bin/simple-phpunit -c phpunit.xml
```
