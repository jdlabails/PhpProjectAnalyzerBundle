

rm -f ${DIR_REPORT}/TEST/cmd.txt ${DIR_REPORT}/TEST/report.txt ${DIR_REPORT}/TEST/cmdManuelle.txt
    
echo "cd ${DIR_SRC}/../" > ${DIR_REPORT}/TEST/cmd.txt
echo "php ${DIR_PHAR}/phpunit.phar -c %%%testconfig%%% --testsuite '%%%testsuite%%%' " > ${DIR_REPORT}/TEST/cmdManuelle.txt
    
if [ ${CODE_COVERAGE} -eq 1 ]
then
    echo "Lancement des tests unitaires AVEC code-coverage"
    echo "php ${DIR_PHAR}/phpunit.phar -c %%%testconfig%%% --testsuite '%%%testsuite%%%'   --coverage-text=${DIR_REPORT}/TEST/coverage.txt --coverage-html ${DIR_REPORT}/TEST/phpUnitReport/ " >> ${DIR_REPORT}/TEST/cmd.txt
    
    if [ -d ${DIR_REPORT}/TEST/phpUnitReport ];then
        rm -rf ${DIR_REPORT}/TEST/phpUnitReport
    fi
    mkdir ${DIR_REPORT}/TEST/phpUnitReport

    rm -f ${DIR_REPORT}/TEST/coverage.txt
    cd ${DIR_SRC}/../
    php ${DIR_PHAR}/phpunit.phar -c %%%testconfig%%% --testsuite "%%%testsuite%%%"   --coverage-text=${DIR_REPORT}/TEST/coverage.txt --coverage-html ${DIR_REPORT}/TEST/phpUnitReport/ > ${DIR_REPORT}/TEST/report.txt 2>&1
else
    echo "Lancement des tests unitaires SANS code-coverage"
    echo "php ${DIR_PHAR}/phpunit.phar -c %%%testconfig%%% --testsuite '%%%testsuite%%%'  " >> ${DIR_REPORT}/TEST/cmd.txt
    
    cd ${DIR_SRC}/../
    php ${DIR_PHAR}/phpunit.phar -c %%%testconfig%%% --testsuite "%%%testsuite%%%"  > ${DIR_REPORT}/TEST/report.txt 2>&1
fi
