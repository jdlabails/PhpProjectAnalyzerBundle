
rm -f ${DIR_REPORT}/CPD/*.txt

echo "Analyse Copy-Paste"
echo "php ${DIR_BIN}/phpcpd ${DIR_SRC}" > ${DIR_REPORT}/CPD/cmd.txt
echo "php ${DIR_BIN}/phpcpd ${DIR_SRC}" > ${DIR_REPORT}/CPD/cmdManuelle.txt

php ${DIR_BIN}/phpcpd ${DIR_SRC} > ${DIR_REPORT}/CPD/report.txt 2>&1
