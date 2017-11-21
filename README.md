Dockerfiles
-----------
All you need to build and run an Apache2/httpd multihost server with as many VHOSTs as needed.
For this all served data and all config files are kept outside the Docker container and are forwarded into the running Docker container.

This repository contains the Dockerfiles to create the following Docker images
 * centos7_php7_httpd
 * centos7_php56_httpd
 * ubuntu_php7_apache2
 * ubuntu_php56_apache2

For every VHOST served the following files are needed on Dockerhost
 * a directory inside /var/www/ that contains the data served
 * a .config file inside /var/sites-enabled that will define a named VHOST
 * a WRITABLE directory inside /var/moodledata/ to provide a separate space for every Moodle instance served (not needed for any non-Moodle VHOST)

There is a CLI tool 'deploy_vhost' that will do most of the work - all you need is to provide a matching directory in the webroot folder.

build_all.sh
------------
<i>needs to run as superuser.</i>

This script will build all Docker images and will prepare the Docker host for running the multihost.
It will install the basic config file into
 * /etc/multihost.conf

The script will wait for any input and then automatically open an editor and ask to update the settings in this file as this is needed to complete the setup.

You will need to adopt the settings to these repositories on the <b>host</b> machine according to your setup:
 * sites_enabled_path	: path to the folder 'sites-enabled' that contains the server .config files which will be used by apache2/httpd in the Docker container
 * www_path		: path to the general document root for the apache2/httpd server - it will comtain subdirectories that should match the .config files in sites-enabled
 * moodledata_path	: path to the general moodledata folder - it will contain a subrirectory for every (Moodle-)server configured. It should contain a symlink to /filedir to access moodledata files. This way the cache is retained even between server restarts.
 * filedir_path		: path to a moodledata/filedir. This repository which will be available as /filedir in running Docker comntainers and from there can be mapped multiple times via symlink into the moodledata used by the VHOSTs in the Docker container.

After the setting have been changed/confirmed the installation will finish by calling the 'install_commands.sh' command file (see below).

install_commands.sh
-------------------
<i>needs to run as superuser</i>

This script will install/update 3 multihost CLI commands (please see descriptions of these commands further below):
 * run_multihost - run or restart a multihost instance
 * restart_multihost - restart the Apache2/httpd server inside the Docker container to allow changes in configuration
 * deploy_vhost - this command allows to ad a new VHOST to the multihost server

 It will be called by the 'build_all.sh' script but can be evoked separately in case you need to update the commands.

run_multihost
-------------
<i>located at: /usr/local/bin/</i>

To run a Docker container a script is provided. It should ideally be placed in your PATH (e.g. /usr/local/bin/run_multihost) and needs to be executable.

You can run a Docker container  with one of the following options:
 * run_multihost 		= centos7_php7_httpd (default)
 * run_multihost -ou 		= ubuntu_php7_apache2
 * run_multihost -p5 		= centos7_php56_httpd
 * run_multihost -ou -p5 	= ubuntu_php56_apache2 

This script will also create/update the /usr/local/bin/restart_multihost command (see below) according to the selected container

restart_multihost
----------------
<i>located at: /usr/local/bin</i>

This command allows to restart the Apache2/httpd server inside the Docker container so to reload changes in config files.

deploy_vhost <i>servername</i>
------------------------------
<i>located at: /usr/local/bin</i>

With this command a new VHOST with the name <i>servername</i> will be added to the multihost server.

The servername must be unique and in the webroot of the multihost server needs to be a folder with the same name.
When the VHOST has been deplyed the Apache2/httpd service will be restarted. 

You then will need to change your local /etc/hosts file accordingly to access the VHOST.

default.configuration
---------------------
When running 'build_all.sh' this file will automatically be copied into the 'sites-enabled' folder - this is why it is crucial that the multihost.conf file is updated with correct information during the initial build using build_all.sh.

It will be used by the 'deploy_vhost' command when creating a config file for the new VHOST. It needs to have the name 'default.configuration'.

multihost.conf
--------------
<i>located at: /etc/multihost.conf</i>

This file contains all user defined settings for the multihost. It contains the settings that were edited or confirmed when running the build_all.sh script and is needed by the commands 'run_multihost' and 'deploy_vhost' to work properly.

remove_vhost <i>servername</i>
------------------------------
<i>located at: /usr/local/bin</i>

This command will remove all settings of a given VHOST - but not the web data itself!

uninstall_all.sh
----------------
<i>needs to run as superuser</i>

This will remove the complete multihost docker installation from the machine - but NOT the web data nor any configuration files.

The script will call the remove_commands.sh script.

remove_commands.sh
------------------
<i>needs to run as superuser</i>

This script will remove all multihost CLI commands.

----------------
v.1.0