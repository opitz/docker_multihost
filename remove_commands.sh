#!/usr/bin/env bash
# script to (re-)install all scripts/commands for multihost
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'remove multihost commands v.1.4'
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

remove_command() {


	if [ ! $1 ]
		then
		usage 'no command given - aborting!'
	fi
    if [ $2 ] 
            then 
            command_path=$2
            if [ ! -d $command_path ]
            	then
            	usage 'installation path not found - aborting!'
            fi
    fi

	if [ ! -f $command_path/$1 ]
        	then
		echo "--> Could not find command file '$1' - skipping!"
	else
    	sudo rm $command_path/$1
		echo "--> command '$1' has been removed from $command_path/."
	fi
}

remove_command quit_multihost
remove_command show_multihost_db
remove_command run_multihost
remove_command restart_multihost
remove_command purge_moodlecache
remove_command multihost_default
remove_command enable_vhost
remove_command disable_vhost

echo "--> Done!"
echo ' '

