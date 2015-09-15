
rm -f ${DIR_REPORT}/LOC/*.txt

echo "Mesures des sources par PhpLoc"
echo "php ${DIR_PHAR}/phploc.phar --log-xml ${DIR_REPORT}/LOC/phploc.xml ${DIR_SRC}" > ${DIR_REPORT}/LOC/cmd.txt
echo "php ${DIR_PHAR}/phploc.phar ${DIR_SRC}" > ${DIR_REPORT}/LOC/cmdManuelle.txt

php ${DIR_PHAR}/phploc.phar --log-xml ${DIR_REPORT}/LOC/phploc.xml ${DIR_SRC} > ${DIR_REPORT}/LOC/report.txt 2>&1
