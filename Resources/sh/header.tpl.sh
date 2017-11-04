#!/bin/bash
################################################################################
#
# Script Project Analyzer
#
# Date       Auteur       : Contenu
# 2014-09-15 Jean-David Labails   : creation du script
#
################################################################################


#set -e  # fail on first error

#Parametres generaux
DIR_SRC=%%%dir_src%%%  #Repertoire des sources à analyser
DIR_PA=%%%dir_pa%%%
DIR_BIN=%%%dir_bin%%%
DIR_REPORT=${DIR_PA}/reports
DIR_PHAR=%%%dir_phar%%%
DIR_JETON=${DIR_PA}/jetons
LOCK_PATH=%%%lock_path%%%

#chmod -R 777 ${DIR_REPORT}

touch ${DIR_JETON}/jetonAnalyse

START=`date +%s`

# Reset all variables that might be set
CODE_COVERAGE=0
DOC=0


while getopts cd opt
do
    case $opt in
        c)
            CODE_COVERAGE=1
            ;;
        d)
            DOC=1
            ;;
    esac
done
