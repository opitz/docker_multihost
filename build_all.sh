#!/usr/bin/env bash
# script to build all docker images for multihost
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'multihost builder v.1.0'
echo '--------------------------------------------------------'

if [ $1 == 'nodocker' ]
	then
	echo "--> Bypassing building Docker images."
else
	docker build -t centos7_php7_httpd centos7_php7_httpd 
	docker build -t centos7_php56_httpd centos7_php56_httpd
	docker build -t ubuntu_php7_apache2 ubuntu_php7_apache2
	docker build -t ubuntu_php56_apache2 ubuntu_php56_apache2
fi

# insatlling/updating the configuration file
if [ -f /etc/multihost.conf ]
	then
	echo "Found an existing '/etc/multihost.conf' file!."
else
	sudo cp multihost.conf /etc/multihost.conf
	sudo chmod 777 /etc/multihost.conf
	echo "A '/etc/multihost.conf' file has been placed and needs to be configured in order to finish the inital setup."
fi

echo "Please press any key to open that file with an editor, make any necessay changes and save them."
echo "The setup will continue after you have closed the editor."
read -n 1 -s
sudo nano /etc/multihost.conf

# check for the updated config file and read the settings 
if [ ! -f /etc/multihost.conf ]
	then
	echo "No configuration file found at /etc/multihost.conf - aborting!"
	exit 1
fi
. /etc/multihost.conf

# install the multihost cli commands
if [ ! -f install_commands.sh ]
	then
	echo "CRITICAL: No command installation script found - skipping installation of multihost commands!"
else
	sudo ./install_commands.sh
fi

sudo cp default.configuration ${sites_enabled_path}/default.configuration
sudo chmod 777 ${sites_enabled_path}/default.configuration

echo ' '
if [ ! -f $command_path/run_multihost ]
	then
	echo "FAILURE! The 'run_multihost' command could not be found!"
	echo "You may want to check your installation."
else
	echo 'All Done!'
	echo "You may run 'run_multihost' to start the multihost server now."
fi
echo ' '
