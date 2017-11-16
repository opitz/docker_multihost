#!/usr/bin/env bash
# script to build all docker images for multihost
echo 'multihost builder v.1.0'
docker build -t centos7_php7_httpd centos7_php7_httpd
docker build -t centos7_php56_httpd centos7_php56_httpd
docker build -t ubuntu_php7_apache2 ubuntu_php7_apache2
docker build -t ubuntu_php56_apache2 ubuntu_php56_apache2

sudo cp run_multihost /usr/sbin/run_multihost
sudo chmod  777 /usr/sbin/run_multihost

sudo touch /usr/sbin/reboot_multihost
sudo chmod 777 /usr/sbin/reboot_multihost

sudo cp multihost.conf /etc/multihost.conf
sudo chmod 777 /etc/multihost.conf
echo '--------------------------------------------------------'
echo 'All Done!'
echo 'Please edit /var/multihost.conf according to your needs!'
