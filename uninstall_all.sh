#!/usr/bin/env bash
# script to build all docker images for multihost
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'multihost uninstaller v.1.1'
echo '--------------------------------------------------------'

if [ $1 == 'nodocker' ]
	then
	echo "--> Bypassing removing Docker images."
else
# 	remove any exsiting multihost container, running or not
	docker rm -f multihost_centos7_php7_httpd >/dev/null 2>/dev/null
	docker rm -f multihost_centos7_php56_httpd >/dev/null 2>/dev/null
	docker rm -f multihost_ubuntu_php7_apache2 >/dev/null 2>/dev/null
	docker rm -f multihost_ubuntu_php56_apache2 >/dev/null 2>/dev/null
#	now remove all Docker images
	docker rmi -f centos7_php7_httpd 
	docker rmi -f centos7_php56_httpd
	docker rmi -f ubuntu_php7_apache2
	docker rmi -f ubuntu_php56_apache2
fi

# check for the config file and read the settings - for the last time!
if [ ! -f /etc/multihost.conf ]
	then
	echo "No configuration file found at /etc/multihost.conf - aborting!"
	exit 1
fi
. /etc/multihost.conf

# remove the multihost cli commands
if [ ! -f remove_commands.sh ]
	then
	echo "CRITICAL: No command removal script found - skipping removal of multihost commands!"
else
	sudo ./remove_commands.sh
fi

# removing the sites-enabled folder and all its content
sudo rm -r ${sites_enabled_path}/ >/dev/null 2>/dev/null
echo "--> $sites_enabled_path' directory has been removed."

# removing the configuration file
if [ -f /etc/multihost.conf ]
	then
	sudo rm /etc/multihost.conf >/dev/null 2>/dev/null
	echo "--> Removed an existing '/etc/multihost.conf' file."
fi

echo 'All Done!'
echo "The multihost server has been removed,"
echo ' '
