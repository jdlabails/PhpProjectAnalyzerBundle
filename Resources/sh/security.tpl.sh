
rm -f ${DIR_REPORT}/SECURITY/*.txt

echo "Security checker"
echo "php ${DIR_PHAR}/security-checker.phar security:check ${LOCK_PATH} --format=simple" > ${DIR_REPORT}/SECURITY/cmd.txt

php ${DIR_PHAR}/security-checker.phar  security:check ${LOCK_PATH} --format=simple > ${DIR_REPORT}/SECURITY/report.txt
