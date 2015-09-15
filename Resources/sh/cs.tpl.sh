
rm -f ${DIR_REPORT}/CS/*.txt

echo "Analyse code sniffer"
echo "php ${DIR_PHAR}/phpcs.phar --report-file=${DIR_REPORT}/CS/report.txt --extensions=php --standard=%%%standard%%% ${DIR_SRC}" > ${DIR_REPORT}/CS/cmd.txt
echo "php ${DIR_PHAR}/phpcs.phar --extensions=php --standard=%%%standard%%% ${DIR_SRC}" > ${DIR_REPORT}/CS/cmdManuelle.txt
echo "php ${DIR_PHAR}/phpcbf.phar --extensions=php --standard=%%%standard%%% --no-patch ${DIR_SRC}" > ${DIR_REPORT}/CS/cmdRep.txt

php ${DIR_PHAR}/phpcs.phar --report-file=${DIR_REPORT}/CS/report.txt --extensions=php --standard=%%%standard%%% ${DIR_SRC} > ${DIR_REPORT}/CS/summary.txt 2>&1
