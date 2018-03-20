#!/usr/bin/env bash

# script to enable a VHOST in Docker multihost
# m.opitz@qmul.ac.uk | 2017-12-11

# this script will run INSIDE the Docker container so all paths need to be related to that server.

sites_enabled_path="/etc/httpd/sites-enabled"
moodledata_path="/var/moodledata"
www_path="/var/www"
servername=$1

if [ ! $1 ]
	then
	exit 1
fi

if [ ! -f ${sites_enabled_path}/default.configuration ]
	then
	echo "Could not find a ${sites_enabled_path}/default.configuration file - aborting!"
	exit 1
fi

cp ${sites_enabled_path}/default.configuration ${sites_enabled_path}/${servername}.conf

if [ "`uname`" == "Darwin"  ] # this is most likely a Mac - they need some extra care...
	then
	sed -i ' ' "s/TheServerName/${servername}/g" ${sites_enabled_path}/${servername}.conf
else
		sed -i "s/TheServerName/${servername}/g" ${sites_enabled_path}/${servername}.conf
fi

chmod 777 ${sites_enabled_path}/${servername}.conf

# make a symlink into the default multihost webserver to allow alternative access through subdirectory in URL
sudo ln -s ${www_path}/${servername} ${www_path}/html/${servername}

if [ -f $www_path/$servername/blocks/moodleblock.class.php ]
	then
	# this is a Moodle instance - so prepare moodledata and cron job
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
