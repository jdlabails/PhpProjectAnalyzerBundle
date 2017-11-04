#!/bin/bash

rm phpDocumentor.phar
wget http://www.phpdoc.org/phpDocumentor.phar

rm phpmd.phar
wget http://static.phpmd.org/php/latest/phpmd.phar

#si php >= 5.6 telecharge la 5.2 sinon la 4.8
rm phpunit.phar
wget https://phar.phpunit.de/phpunit.phar

#wget https://phar.phpunit.de/phpunit-old.phar

rm behat.phar
wget https://github.com/downloads/Behat/Behat/behat.phar

rm security-checker.phar
wget http://get.sensiolabs.org/security-checker.phar

rm atoum.phar
wget https://github.com/atoum/atoum/releases/download/3.2.0/atoum.phar
