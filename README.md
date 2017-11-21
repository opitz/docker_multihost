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
needs to run as superuser.

This script will build all Docker images and will prepare the Docker host for running the multihost.
It will install the basic config file into
 * /etc/multihost.conf

The script will then automatically open and editor and ask to update the settings in this file as this is needed to complete the setup.

It will then call the 'install_commands.sh' command file (see below) to finish the installation.

install_commands.sh
-------------------
needs to run as superuser

This script will create/update 3 multihost commands:
 * run_multihost - run or restart a multihost instance
 * restart_multihost - restart the Apache2/httpd server inside the Docker container to allow changes in configuration
 * deploy_vhost - this command allows to ad a new VHOST to the multihost server

 It will be called by the 'build_all.sh' script but can be evoked separately in case you need to update the commands.

run_multihost
-------------
located at: /usr/local/bin/

To run a Docker container a script is provided. It should idally placed in your PATH (e.g. /usr/local/bin/run_multihost) and needs to be executable

You can run a Docker container  with one of the following uses:
 * run_multihost 		= centos7_php7_httpd (default)
 * run_multihost -ou 		= ubuntu_php7_apache2
 * run_multihost -p5 		= centos7_php56_httpd
 * run_multihost -ou -p5 	= ubuntu_php56_apache2 

You will need to adopt the settings to the following host repositories according to the situation on the Docker server:
 * sites_enabled_path	: path to the folder that contains the server .config files that will be used by apache2/httpd in the Docker container
 * www_path		: path to the general document root of the apache2/httpd server - it will comtain subdirectories that should match the .config files in sites-enabld
 * moodledata_path	: path to the general moodledata folder - it will contain a subrirectory for every (Moodle-)server configured. It should contain a symlink to /filedir to access moodledata files. This way the cache is retained even between server restarts.
 * filedir_path		: path to a moodledata/filedir. This repository which will be available as /filedir in running Docker comntainers and from there can be mapped multiple times via symlink into the moodledata used by the VHOSTs in the Docker container.

This script will also create/update the /usr/local/bin/restart_multihost command (see below) according to the selected container

restart_multihost
----------------
located at: /usr/local/bin

This command allows to restart the Apache2/httpd server inside the Docker container so to reload changes in config files.

deploy_vhost <i>servername</i>i>
-------------------------
located at: /usr/local/bin

With this command a new VHOST may be added to the multihost server

The servername must be unique and in the webroot of the multihost server needs to be a folder with the same name.
When the server has been deplyed you will need to cange your local /etc/hosts file accordingly to access the VHOST.

default.configuration
---------------------
When running 'build_all.sh' this file will automatically be placed inside the 'sites-enabled' folder - this is why it is crucial that the multihost.conf file is updated with correct information during the initial build using build_all.sh.

It is needed in order for the 'deploy_vhost' command to work. It contains the default configuration for each new vhost and needs to have the name 'default.configuration'.

multihost.config
----------------
located at: /etc/multihost.conf

This file contains all user defined settings for the multihost.

It is needed by the commands 'run_multihost' and 'deploy_vhost' to work properly.

----------------
v.2.1