#!/usr/bin/env bash

# script to enable a VHOST in Docker multihost
# m.opitz@qmul.ac.uk | 2017-12-08
sites_enabled_path="/etc/httpd/sites-enabled"
moodledata_path="/var/moodledata"
servername=$1

if [ "$2" == "no_moodle" ]
	then
	no_moodle=1
fi

if [ ! -f ${sites_enabled_path}/default.configuration ]
	then
	echo "Could not find a ${sites_enabled_path}/default.configuration file - aborting!"
	exit 1
fi

cp ${sites_enabled_path}/default.configuration ${sites_enabled_path}/${servername}.conf

if [ "`uname`" == "Darwin"  ]
	then
	sed -i ' ' "s/TheServerName/${servername}/g" ${sites_enabled_path}/${servername}.conf
else
		sed -i "s/TheServerName/${servername}/g" ${sites_enabled_path}/${servername}.conf
fi

chmod 777 ${sites_enabled_path}/${servername}.conf

if [ ! $no_moodle ]
	then
	if [ -d ${moodledata_path}/${servername} ]
		then
		sudo rm -r ${moodledata_path}/${servername}
	fi
	mkdir ${moodledata_path}/${servername}
	chmod -R 777 ${moodledata_path}/${servername}
	ln -s /filedir ${moodledata_path}/${servername}/filedir
	# if it's a moodle vhost add a crontab entry here
	echo "*/1 * * * * /usr/bin/php  /var/www/${servername}/admin/cli/cron.php >/dev/null" >> ${moodledata_path}/moodle_crontab
	# and install the new crontab
	sudo crontab ${moodledata_path}/moodle_crontab
fi

#reload web server config
sudo systemctl reload httpd
