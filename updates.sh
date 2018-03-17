#!/usr/bin/env bash
# script to update any necessary changes to '/etc/multihost.conf' when updating an existing multihost installation
# it will be called from the 'update_all.sh' script

# check for the updated config file and read the settings 
if [ ! -f /etc/multihost.conf ]
	then
	echo "No configuration file found at /etc/multihost.conf - aborting!"
	exit 1
fi
. /etc/multihost.conf

# 2017-12-13: adding ports used by the Docker servers

if [ "$ssl_port" == "" ]
	then
	echo "--> updating /etc/mutihost.conf with ports"
	sudo echo "# set ports" >> /etc/multihost.conf
	sudo echo "export port=80" >> /etc/multihost.conf
	sudo echo "export ssl_port=443" >> /etc/multihost.conf
	sudo echo "export port2=8080" >> /etc/multihost.conf
	sudo echo "export ssl_port2=8443" >> /etc/multihost.conf
fi

# 2018-03-16: adding userfile_path
if [ "$userfile_path" == "" ]
	then
	echo "--> updating /etc/mutihost.conf with userfile_path"
	sudo echo " " >> /etc/multihost.conf
	sudo echo "# where the web interface user file will be found" >> /etc/multihost.conf
	sudo echo "export userfile_path=/etc/multihost.user" >> /etc/multihost.conf
fi