
rm -f ${DIR_REPORT}/CS/*.txt

echo "Analyse code sniffer"
echo "${DIR_BIN}/phpcbf --report-file=${DIR_REPORT}/CS/report.txt --extensions=php --standard=%%%standard%%% ${DIR_SRC}" > ${DIR_REPORT}/CS/cmd.txt
echo "${DIR_BIN}/phpcbf --extensions=php --standard=%%%standard%%% ${DIR_SRC}" > ${DIR_REPORT}/CS/cmdManuelle.txt
echo "${DIR_BIN}/phpcbf --extensions=php --standard=%%%standard%%% --no-patch ${DIR_SRC}" > ${DIR_REPORT}/CS/cmdRep.txt

${DIR_BIN}/phpcbf  --config-set installed_paths ${DIR_PHAR}/csStandard/
${DIR_BIN}/phpcbf --report-file=${DIR_REPORT}/CS/report.txt --extensions=php --standard=%%%standard%%% ${DIR_SRC} > ${DIR_REPORT}/CS/summary.txt 2>&1
