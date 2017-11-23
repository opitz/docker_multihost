#!/usr/bin/env bash
# script to build all docker images for multihost
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'reinstall all v.1.0'
echo '--------------------------------------------------------'

if [ "$1" == "nodocker" ]
	then
	echo "--> Bypassing Docker images."
	sudo ./uninstall_all.sh nodocker 2>/dev/null
	git pull
	sudo ./build_all.sh nodocker
else
    echo "Removing all multihost docker images and recreating them from the Dockerfiles can take quite some time."
    read -p "Is this what you want? [y/N]" doit
    if [ "${doit:0:1}" != "y" ] && [ "${doit:0:1}" != "Y" ]
        then
        echo "====> OK, aborting..."
        echo "You could run this command with the 'nodocker' option to keep the docker images."
        echo ' '
        exit 1
    fi

	sudo ./uninstall_all.sh 2>/dev/null
	git pull
	sudo ./build_all.sh
fi

