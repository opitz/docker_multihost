#!/usr/bin/env bash
# script to build all docker images for multihost
# 2018-08-08

if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!"
   exit 1
fi

echo ' '
echo 'Docker multihost builder v.1.7.2'
echo '--------------------------------------------------------'

check_path() {
# check if path $1 exists and create it otherwise
	if [ ! $1 ]
		then
		echo "no path given to check (mumble mumble) - ignoring"
	else
		if [ ! -d $1 ]
			then
			echo "--> creating $1"
			sudo mkdir -p $1
			sudo chmod -R 777 $1
		else
			if [ "$2" != "no_chmod" ]
				then
				echo "--> checking path $1"
				sudo chmod -R 777 $1
			else
				echo "--> checking path (no chmod) $1"
			fi
		fi
	fi
}

#-------------------------------------------------------------
check_file() {
# check if file $1 exists and touch it otherwise
	if [ ! $1 ]
		then
		echo "no path given to check (mumble mumble) - ignoring"
	else
		if [ ! -d $1 ]
			then
			echo "--> touching $1"
			sudo touch $1
			sudo chmod 777 $1
		else
			if [ "$2" != "no_chmod" ]
				then
				echo "--> checking file $1"
				sudo chmod 777 $1
			else
				echo "--> checking file (no chmod) $1"
			fi
		fi
	fi
}

#-------------------------------------------------------------

if [ "$1" == "nodocker" ]
	then
	echo "--> Bypassing building Docker images."
else
	docker build -t centos7_php7_httpd centos7_php7_httpd
	docker build -t centos7_php56_httpd centos7_php56_httpd
#	docker build -t ubuntu_php7_apache2 ubuntu_php7_apache2
#	docker build -t ubuntu_php56_apache2 ubuntu_php56_apache2
fi

# installing/updating the configuration file
if [ -f /etc/multihost.conf ]
	then
	echo "Found an existing '/etc/multihost.conf' file!."
else
	sudo cp multihost.conf /etc/multihost.conf
	sudo chmod 777 /etc/multihost.conf
	echo "A '/etc/multihost.conf' file has been placed and needs to be configured in order to finish the initial setup."
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

# check for a users file for the web frontend and (re-)install the default setup if missing
# the default setting is user 'admin' with password 'admin'
if [ ! -f /etc/multihost.user ]
	then
	sudo cp ./multihost.user /etc/multihost.user
	sudo chmod 777 /etc/multihost.user
fi

# install the multihost cli commands
if [ ! -f install_commands.sh ]
	then
	echo "CRITICAL: No command installation script found - skipping installation of multihost commands!"
else
	sudo ./install_commands.sh
fi

# check if a dummy directory '/filedir' exists and create it otherwise
# this directory will be used for symlinks inside the moodledata/vhost folder.
# they look pretty useless from the outside - but inside the docker container the symlinks actually
# points to a existing /filedir directory which has been mapped there when running the docker container
check_path '/filedir' no_chmod

# check if moodledata_path exists and create it otherwise
check_path $moodledata_path
check_file $moodledata_path/moodle_crontab

# check if maharadata_path exists and create it otherwise
check_path $maharadata_path

# check if sites_enabled_path exists and create it otherwise
check_path $sites_enabled_path

sudo cp 000-default.conf ${sites_enabled_path}/000-default.conf
sudo chmod 777 ${sites_enabled_path}/000-default.conf

sudo cp default.configuration ${sites_enabled_path}/default.configuration
sudo chmod 777 ${sites_enabled_path}/default.configuration

# check if basic webroot exists and create it otherwise
# each VHOST will need to have a subdirectory in here
check_path $www_path no_chmod

# check if default html webroot exists and create it otherwise with a simple message
#if [ ! -d ${www_path}/html ]
#	then#
#	sudo mkdir ${www_path}/html
#	echo "${www_path}/html is the default webroot directory for multihost. You may want to replace this with a symlink to one of the VHOSTS provided by this server." > ${www_path}/html/index.html
#	sudo chmod 777 -R ${www_path}/html
#fi

# check if filedir directory exists and create it otherwise
check_path $filedir_path no_chmod

# check if xchange directory exists and create it otherwise
check_path $xchange_path

echo ' '
if [ ! -f $command_path/run_multihost ]
	then
	echo "FAILURE! The 'run_multihost' command could not be found!"
	echo "You may want to check your installation."
else
	# make multihost the default server
	sudo rm -r $www_path/multihost/>/dev/null 2>/dev/null
	sudo rm -rf $www_path/html/>/dev/null 2>/dev/null
	sudo rm -f $www_path/html>/dev/null 2>/dev/null
	cp -r ./multihost $www_path/html
	sudo chmod -R 777 $www_path/html
	sudo chmod +x $www_path/html/cli/*.sh
#	sudo enable_vhost multihost>/dev/null

	sudo run_multihost
	echo '--> The Docker multihost server is now up and running on this host.'
fi
echo ' '
