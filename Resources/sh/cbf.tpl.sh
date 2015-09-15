
echo "Réparation code sniffer"
#rm -f ${DIR_REPORT}/CS/*.txt
echo "php ${DIR_PHAR}/phpcbf.phar --extensions=php --standard=%%%standard%%% ${DIR_SRC} --no-patch" > ${DIR_REPORT}/CS/cbf_cmd.txt
php ${DIR_PHAR}/phpcbf.phar --extensions=php --standard=%%%standard%%% ${DIR_SRC} --no-patch > ${DIR_REPORT}/CS/cbf_summary.txt 2>&1
