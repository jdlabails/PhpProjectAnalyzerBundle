cd ../_phar

rm pdepend.phar
wget http://static.pdepend.org/php/latest/pdepend.phar

rm php-cs-fixer.phar
wget http://get.sensiolabs.org/php-cs-fixer.phar

rm phpDocumentor.phar
wget http://www.phpdoc.org/phpDocumentor.phar

rm phpcbf.phar
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar

rm phpcpd.phar
wget https://phar.phpunit.de/phpcpd.phar

rm phpcs.phar
wget https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar

rm phploc.phar
wget https://phar.phpunit.de/phploc.phar

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
