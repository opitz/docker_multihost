#!/usr/bin/env bash
# script to build all docker images for multihost
echo 'multihost builder v.1.7'
docker build -t centos7_php7_httpd centos7_php7_httpd 
docker build -t centos7_php56_httpd centos7_php56_httpd
docker build -t ubuntu_php7_apache2 ubuntu_php7_apache2
docker build -t ubuntu_php56_apache2 ubuntu_php56_apache2

sudo cp run_multihost /usr/local/bin/run_multihost
sudo chmod  777 /usr/local/bin/run_multihost

sudo cp restart_multihost /usr/local/bin/restart_multihost
sudo chmod 777 /usr/local/bin/restart_multihost

sudo cp deploy_vhost /usr/local/bin/deploy_vhost
sudo chmod  777 /usr/local/bin/deploy_vhost

sudo cp multihost.conf /etc/multihost.conf
sudo chmod 777 /etc/multihost.conf

echo '--------------------------------------------------------'
echo "A '/etc/multihost.conf' file has been placed and needs to be configured in order to finish the inital setup."
echo "Please press any key to open that file with an editor, make the necessay changes and save them."
echo "The setup will continue after you have closed the editor."
read -n 1 -s
sudo nano /etc/multihost.conf

# check for the config file and get the settings
if [ ! -f /etc/multihost.conf ]
	then
	echo "No configuration file found at /etc/multihost.conf - aborting!"
	exit 1
fi
. /etc/multihost.conf

sudo cp default.configuration ${sites_enabled_path}/default.configuration
sudo chmod 777 ${sites_enabled_path}/default.configuration

echo ' '
echo 'All Done!'
echo "You may run 'run_multihost' to start the multihost server now."
echo ' '
