#!/usr/bin/env bash
# script to (re-)install all scripts/commands for multihost
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'install multihost commands v.1.0'
echo '--------------------------------------------------------'
usage() {
	if [ $1 ]
		then
		echo "ERROR: $1";
	fi
	echo "Usage: $0 <command_name>" 1>&2;
	exit 1;
}

# 	check for the updated config file and read the settings 
	if [ ! -f /etc/multihost.conf ]
		then
		echo "No configuration file found at /etc/multihost.conf - aborting!"
		exit 1
	fi
	. /etc/multihost.conf

install_command() {


	if [ ! $1 ]
		then
		usage 'no command given - aborting!'
	fi
    if [ $2 ] 
            then 
            command_path=$2
    fi

	if [ ! -f $1 ]
        	then
		echo "Could not find command file '$1' - skipping!"
	else
		if [ -f $command_path/$1 ]
			then
			update=1
		else
			update=0
		fi

    	sudo cp $1 $command_path/$1
    	sudo chmod  777 $command_path/$1
		if [ $update -eq 1 ]
			then
			echo "--> command '$1' has been updated in $command_path/."
		else
			echo "--> command '$1' has been installed into $command_path/."
		fi
	fi
}

install_command run_multihost
install_command restart_multihost
install_command deploy_vhost
install_command remove_vhost
install_command multihost_default

echo Done!
echo ' '

