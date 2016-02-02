
rm -f ${DIR_REPORT}/SECURITY/*.txt

echo "Security checker"
echo "php ${DIR_PHAR}/security-checker.phar security:check ${DIR_SRC}/../composer.lock" > ${DIR_REPORT}/SECURITY/cmd.txt

php ${DIR_PHAR}/security-checker.phar  security:check ${DIR_SRC}/../composer.lock
