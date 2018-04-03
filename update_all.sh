#!/usr/bin/env bash
# script to update the current installation of Docker multihost on this machine.
# it will save the config and users files before perdorming a complete uninstall of the current installation
# followed by a complete rebuild using the version in this directory
# the 'docker' option will force the removal and rebuild of the Docker images as well
# 2018-03-17
if [[ $EUID -ne 0 ]]; then
   echo "SORRY! This script must be run as root/superuser!" 
   exit 1
fi

echo ' '
echo 'update_all.sh v.1.6'
echo '--------------------------------------------------------'

# make a backup of the multihost.conf and multihost.user files and of the currently enabled VHOSTs
if [ -f /etc/multihost.conf ]
    then
    sudo cp /etc/multihost.conf /etc/multihost.conf.bak
    sudo cp /etc/multihost.user /etc/multihost.user.bak
    . /etc/multihost.conf

    # make backups of the currently configured VHOSTs
    enabled_vhosts=`ls $sites_enabled_path`
fi

if [ "$1" == "docker" ]
	then
    echo "Removing all multihost docker images and recreating them from the Dockerfiles can take quite some time."
    read -p "Is this what you want? [y/N]" doit
    if [ "${doit:0:1}" != "y" ] && [ "${doit:0:1}" != "Y" ]
        then
        echo "====> OK, aborting..."
        echo "You could run this command without the 'docker' option to keep the docker images."
        echo ' '
        exit 1
    fi
    # ok, you mean it - so uninstall everything
    sudo ./uninstall_all.sh 2>/dev/null
else
    echo "--> Keeping Docker images. Run with 'docker' option to force rebuild Docker images too."    
    # uninstall everything but the docker images
    sudo ./uninstall_all.sh nodocker 2>/dev/null
fi

#    git pull

#restore the multihost.conf file
if [ -f /etc/multihost.conf.bak ]
    then
    sudo cp /etc/multihost.conf.bak /etc/multihost.conf
fi
#restore the multihost.user file
if [ -f /etc/multihost.user.bak ]
    then
    sudo cp /etc/multihost.user.bak /etc/multihost.user
    sudo chmod 777 /etc/multihost.user
fi
# apply updates where applicable
if [ -f updates.sh ]
    then
    sudo ./updates.sh
fi
# install everything
sudo ./build_all.sh


# re-enable all previously enabled VHOSTs
#for vhost in $enabled_vhosts
#do
#    if [[ ! "${vhost}" == *"default"* && "${vhost%.*}" != "multihost" ]]
#        then
#        sudo enable_vhost ${vhost%.*} >/dev/null
#        echo "--> ${vhost%.*} has been re-enabled"
#    fi
#done
echo " "
echo "Update complete!"
echo " "

