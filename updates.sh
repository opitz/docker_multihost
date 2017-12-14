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
	sudo echo "port=80" >> /etc/multihost.conf
	sudo echo "ssl_port=443" >> /etc/multihost.conf
	sudo echo "port2=8080" >> /etc/multihost.conf
	sudo echo "ssl_port2=8443" >> /etc/multihost.conf
fi