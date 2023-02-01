#!/bin/bash
datasemanadir=`date -d "7 day ago" '+%d-%m-%Y'`
dataontem=`date -d "1 day ago" '+%Y-%m-%d'`
dataontemdir=`date -d "1 day ago" '+%d-%m-%Y'`
datahoje=`date -d "today" '+%Y-%m-%d'`
cd /root/backupvps || exit

function deletoldbck {
if [ -d "$datasemanadir" ]; then
	rm -rf $datasemanadir
fi
}

function chkdirbck {
if [ -d "$dataontemdir" ]; then
	moveoldbck
else
	mkdir $dataontemdir
	chkdirbck
fi
}
function moveoldbck {
ls | grep $dataontem | xargs -d '\n' mv -t $dataontemdir
}
chkdirbck
deletoldbck
cd /root || exit
exit 0
