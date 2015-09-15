
rm -f ${DIR_REPORT}/MD/*.txt

echo "Analyse Mess Detector"
echo "${DIR_PHAR}/phpmd.phar ${DIR_SRC} text %%%rule_set%%% --reportfile ${DIR_REPORT}/MD/report.txt" > ${DIR_REPORT}/MD/cmd.txt
echo "${DIR_PHAR}/phpmd.phar ${DIR_SRC} text %%%rule_set%%%" > ${DIR_REPORT}/MD/cmdManuelle.txt

php ${DIR_PHAR}/phpmd.phar ${DIR_SRC} text %%%rule_set%%% --reportfile ${DIR_REPORT}/MD/report.txt
