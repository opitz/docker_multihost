#!/usr/bin/env bash
# script to build all docker images for multihost
# 2018-03-16
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'Docker multihost uninstaller v.1.2.1'
echo '--------------------------------------------------------'

if [ "$1" == "nodocker" ]
	then
	echo "--> Bypassing removing Docker images."
else
    echo "This will remove all multihost docker images as well!"
    echo "(You will be able to recreate them from Dockerfiles but that will take some time.)"
    read -p "Is this what you want? [y/N]" doit
    if [ "${doit:0:1}" != "y" ] && [ "${doit:0:1}" != "Y" ]
        then
        echo "====> OK, aborting..."
        echo "You could run this command with the 'nodocker' option to keep the Docker images."
        echo ' '
        exit 1
    fi

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
if [ -d $sites_enabled_path ]
	then
	sudo rm -r $sites_enabled_path >/dev/null 2>/dev/null
	echo "--> '$sites_enabled_path' directory has been removed."
fi

# removing the moodledata folder and all its content
if [ -d $moodledata_path ]
	then
	sudo rm -r $moodledata_path >/dev/null 2>/dev/null
	echo "--> '$moodledata_path' directory has been removed."
fi

# removing the moodledata-help webroot folder and all its content
if [ -d $www_path/multihost ]
	then
	sudo rm -r $www_path/multihost >/dev/null 2>/dev/null
	echo "--> '$www_path/multihost' directory has been removed."
fi

# removing the html default webroot if it is a link only
if [ -L $www_path/html ]
	then
	sudo rm -r $www_path/html >/dev/null 2>/dev/null
	echo "--> '$www_path/html' symlink has been removed."
fi

# removing the web interface user file
if [ -f /etc/multihost.user ]
	then
	sudo rm /etc/multihost.user >/dev/null 2>/dev/null
	echo "--> existing '/etc/multihost.user' file has been removed."
fi

# removing the configuration file
if [ -f /etc/multihost.conf ]
	then
	sudo rm /etc/multihost.conf >/dev/null 2>/dev/null
	echo "--> existing '/etc/multihost.conf' file has been removed."
fi

echo 'All Done!'
echo "The Docker multihost server has been removed,"
echo ' '
