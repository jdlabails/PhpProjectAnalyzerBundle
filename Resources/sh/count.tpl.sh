
echo "Comptage"
rm -f ${DIR_REPORT}/COUNT/*.txt
find ${DIR_SRC}/ -type d | wc -l > ${DIR_REPORT}/COUNT/nbDossier.txt
find ${DIR_SRC}/ -type f | wc -l > ${DIR_REPORT}/COUNT/nbFichier.txt
find ${DIR_SRC}/ -type f -name "*.php" | wc -l > ${DIR_REPORT}/COUNT/nbPHP.txt
find ${DIR_SRC}/ -type f -name "*.css" | wc -l > ${DIR_REPORT}/COUNT/nbCSS.txt
find ${DIR_SRC}/ -type f -name "*.js" | wc -l > ${DIR_REPORT}/COUNT/nbJS.txt
find ${DIR_SRC}/ -type f -name "*.min.css" | wc -l > ${DIR_REPORT}/COUNT/nbLibCSS.txt
find ${DIR_SRC}/ -type f -name "*.min.js" | wc -l > ${DIR_REPORT}/COUNT/nbLibJS.txt
find ${DIR_SRC}/ -type f -name "*.twig" | wc -l > ${DIR_REPORT}/COUNT/nbTwig.txt
find ${DIR_SRC}/ -type d -name "*Bundle" | wc -l > ${DIR_REPORT}/COUNT/nbBundle.txt

 