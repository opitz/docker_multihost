#!/usr/bin/env bash
# script to build all docker images for multihost
echo 'multihost builder v.1.2'
docker build -t centos7_php7_httpd centos7_php7_httpd
docker build -t centos7_php56_httpd centos7_php56_httpd
docker build -t ubuntu_php7_apache2 ubuntu_php7_apache2
docker build -t ubuntu_php56_apache2 ubuntu_php56_apache2

sudo cp run_multihost /usr/sbin/run_multihost
sudo chmod  777 /usr/sbin/run_multihost

sudo touch /usr/sbin/reboot_multihost
sudo chmod 777 /usr/sbin/reboot_multihost

sudo cp deploy_vhost /usr/sbin/deploy_vhost
sudo chmod  777 /usr/sbin/deploy_vhost

sudo cp multihost.conf /etc/multihost.conf
sudo chmod 777 /etc/multihost.conf

echo '--------------------------------------------------------'
echo "A '/etc/multihost.conf' file has been placed and needs to be configured in order to finish the inital setup."
echo "Please press any key to open that file with an editor, make the necessay changes and save them."
echo "The setup will continue after you have closed the editor."
sudo nano /etc/multihost.conf

# check for the config file and get the settings
if [ ! -f /etc/multihost.conf ]
	then
	echo No configuration file found at /etc/multihost.conf file found - aborting!
	exit 1
fi
. /etc/multihost.conf

sudo cp default.configuration ${sites_enabled_path}/default.configuration
sudo chmod 777 ${sites_enabled_path}/default.configuration

echo 'All Done!'
echo "You may run 'run_multihost' to start the multihost server now."
