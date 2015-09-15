

rm -f ${DIR_REPORT}/TEST/cmd.txt ${DIR_REPORT}/TEST/report.txt ${DIR_REPORT}/TEST/cmdManuelle.txt
    
echo "cd ${DIR_SRC}/../" > ${DIR_REPORT}/TEST/cmd.txt
echo "%%%pathAtoum%%% -d %%%dirTestAtoum%%% " > ${DIR_REPORT}/TEST/cmdManuelle.txt
    
if [ ${CODE_COVERAGE} -eq 1 ]
then
    echo "Lancement des tests unitaires atoum AVEC code-coverage"
    echo "%%%pathAtoum%%% -d %%%dirTestAtoum%%% -c ${DIR_PA}assets/php/atoum.cc.php" >> ${DIR_REPORT}/TEST/cmd.txt
    
    cd ${DIR_SRC}/../

    %%%pathAtoum%%% -d %%%dirTestAtoum%%% -c ${DIR_PA}assets/php/atoum.cc.php > ${DIR_REPORT}/TEST/report.txt 2>&1
else
    echo "Lancement des tests unitaires atoum SANS code-coverage"
    echo "%%%pathAtoum%%% -d %%%dirTestAtoum%%%" >> ${DIR_REPORT}/TEST/cmd.txt
    
    cd ${DIR_SRC}/../
    %%%pathAtoum%%% -d %%%dirTestAtoum%%% > ${DIR_REPORT}/TEST/report.txt 2>&1
fi
