#!/usr/bin/env bash

# script to remove a VHOST from multihost
# m.opitz@qmul.ac.uk | 2017-12-06
sites_enabled_path="/etc/httpd/sites-enabled"
moodledata_path="/var/moodledata"
servername=$1

if [ -f ${sites_enabled_path}/${servername}.conf ]
	then
	rm -f ${sites_enabled_path}/${servername}.conf
	echo "removing $servername.conf"
fi

if [ -d ${moodledata_path}/${servername} ]
	then
	rm -r -f ${moodledata_path}/${servername} >/dev/null 2>/dev/null
fi

# remove server from moodle_crontab
if [ -f ${moodledata_path}/moodle_crontab ]
	then
	# remove the line(s) that contain "/the_servername/" from ${moodledata_path}/moodle/crontab
	if [ "`uname`" == "Darwin" ]
		then
		sed -i ' ' "/\/${servername}\//d" ${moodledata_path}/moodle_crontab
	else
		sed -i "/\/${servername}\//d" ${moodledata_path}/moodle_crontab
	fi
	# and install the new crontab
	sudo crontab ${moodledata_path}/moodle_crontab
fi

#reload web server config
sudo systemctl reload httpd

