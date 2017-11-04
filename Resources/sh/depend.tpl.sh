
rm -f ${DIR_REPORT}/DEPEND/*.txt

echo "Calcul de metriques par PhpDepend"
echo "${DIR_BIN}/pdepend --summary-xml=${DIR_REPORT}/DEPEND/summary.xml --jdepend-chart=${DIR_REPORT}/DEPEND/jdepend.svg --overview-pyramid=${DIR_REPORT}/DEPEND/pyramid.svg ${DIR_SRC}"  > ${DIR_REPORT}/DEPEND/cmd.txt
echo "${DIR_BIN}/pdepend ${DIR_SRC}"  > ${DIR_REPORT}/DEPEND/cmdManuelle.txt

${DIR_BIN}/pdepend --summary-xml=${DIR_REPORT}/DEPEND/summary.xml --jdepend-chart=${DIR_REPORT}/DEPEND/jdepend.svg --overview-pyramid=${DIR_REPORT}/DEPEND/pyramid.svg ${DIR_SRC} > ${DIR_REPORT}/DEPEND/report.txt 2>&1
