
if [ ${DOC} -eq 1 ]
then
    rm -rf ${DIR_REPORT}/DOCS/*
    echo "Rédaction de la documentation"
    echo "php ${DIR_PHAR}/phpDocumentor.phar -d ${DIR_SRC} -t ${DIR_REPORT}/DOCS" > ${DIR_REPORT}/DOCS/cmd.txt
    php ${DIR_PHAR}/phpDocumentor.phar -d ${DIR_SRC} -t ${DIR_REPORT}/DOCS > ${DIR_REPORT}/DOCS/report.txt 2>&1
fi
