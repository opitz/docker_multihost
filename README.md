Dockerfiles
-----------
All you need to build and run an Apache2/httpd multihost server with as many VHOSTs as needed.
For this all served data and all config files are kept outside the Docker container and are forwarded into the running Docker container.

For every VHOST served the following files are needed on Dockerhost
 * a directory inside /var/www/ that contains the data served
 * a .config file inside /var/sites-enabled that will define a named VHOST
 * a WRITABLE directory inside /var/moodledata/ to provide a separate space for every Moodle instance served (not needed for any non-Moodle VHOST)

This repository contains the Dockerfiles to create the following Docker images
 * centos7_php7_httpd
 * centos7_php56_httpd
 * ubuntu_php7_apache2
 * ubuntu_php56_apache2

build_all.sh
------------
This script will build all Docker images and will prepare the Docker host for running the multihost.
It will install the basic config file into
 * /etc/multihost.conf
This will have to be changed according to your setup.

Additinally it will create 3 new commands
 * run_multihost - run or restart a multihost instance
 * reboot_multihost - reboot the Apache2/httpd server inside the Docker container to allow changes in configuration
 * deploy_vhost - this command allows to ad a new VHOST to the multihost server

run_multihost
-------------
located at: /usr/sbin/

To run a Docker container a script is provided. It should idally placed in your PATH (e.g. /usr/sbin/run_multihost) and needs to be executable

You can run a Docker container  with one of the following uses:
 * run_multihost 		= centos7_php7_httpd (default)
 * run_multihost -ou 		= ubuntu_php7_apache2
 * run_multihost -p5 		= centos7_php56_httpd
 * run_multihost -ou -p5 	= ubuntu_php56_apache2 

You will need to adopt the settings to the host repositories to the situation on the Docker server:
 * sites_enabled_path	: path to the folder that contains the server .config files that will be used by apache2/httpd in the Docker container
 * www_path		: path to the general document root of the apache2/httpd server - it will comtain subdirectories that should match the .config files in sites-enabld
 * moodledata_path	: path to the general moodledata folder - it will contain a subrirectory for every (Moodle-)server configured. It should contain a symlink to /filedir to access moodledata files. This way the cache is retained even between server restarts.
 * filedir_path		: path to a moodledata/filedir. This repository which will be available as /filedir in running Docker comntainers and from there can be mapped multiple times via symlink into the moodledata used by the VHOSTs in the Docker container.

This script will also update the /usr/sbin/reboot_multihost command according to the selected container

reboot_multihost
----------------
located at: /usr/sbin

This command allows to reboot the Apache2/httpd server inside the Docker container so to reload changes in config files.

deploy_vhost <servername>
-------------------------
located at: /usr/sbin

With this command a new VHOST may be added to the multihost server

The servername must be unique and in the webroot of the multihost server needs to be a folder with the same name.
When the server has been deplyed you will need to cange your local /etc/hosts file accordingly to access the VHOST.

default.configuration
---------------------
This file needs to be placed inside the 'sites-enabled' repository in order for the 'deploy_vhost' command to work. It contains the default configuration for each new vhost and needs to have the name 'default.configuration'.

multihost.config
----------------
located at: /etc/multihost.conf

This file contains all settings for the multihost and needs to be configured according to the situation


