#!/bin/bash
clear
find /var/tmp/ -type f -iname "*.painel" -exec mv -n {} /var/tmp/ \;
[[ $(ls /var/tmp/| grep -c '.painel') != '0' ]] && {
    for i in $(ls /var/tmp/| grep '.painel'); do 
        tempo=$(cat /var/tmp/$i| cut -d : -f1)
        iduser=$(cat /var/tmp/$i| cut -d : -f2)
        [[ $(date +%s) -gt $tempo ]] && {
	    echo expirou
	    php /var/www/html/pages/system/cron.ssh.teste.php ${iduser}
	    rm /var/tmp/$i
	}
    done
}