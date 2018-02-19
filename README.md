Docker multihost
----------------
All you need to build and run an Apache2/httpd multihost server with as many VHOSTs as needed.
For this all served data and all config files are kept outside the Docker container and are forwarded into the running Docker container.

Quickstart
----------
* make sure no other processes are using ports 80 or 443 on the dedicated host
* git clone this repository to the dedicated host
* cd into the resulting directory (e.g. docker_moodle)
* build and start the server by running 'sudo ./build_all.sh'. During installation you will be asked to edit/confirm the configuration of paths used. Directores will be created where they are not already present.
* point your browser to the IP address of the server

The multihost web interface
---------------------------
After a sucessful build of the server (see below) it will by default serve the administation web interface that will show technical details and allows configuration of the VHOSTs served. For this it will list all the potential VHOSTs - that is all the directories found in the web root of the server (e.g. /var/www) - which can be enabled with the click of a button. Once enabled a VHOST may be contacted by using it's name -  but this will require changing the local /etc/hosts file accordingly and relate the name of the VHOST to the IP address the server is actually running on.

The contents of the default VHOST will be shown whenever the generic DNS name or the IP address of the server is used as an URL. You can declare any of the enabled VHOSTs as the default one - but remember to declare a 'multihost' entry in your /etc/hosts file before switching the default VHOST so you are able to return to the admin interface easily by using "http://multihost" in your browser.

You may disable any VHOST with the click of a button  - but not the current default VHOST and not "multihost" as this containes the web interface.

Dockerfiles
-----------
This repository contains the Dockerfiles to create the following Docker images
 * centos7_php7_httpd
 * centos7_php56_httpd
 * ubuntu_php7_apache2
 * ubuntu_php56_apache2

For every VHOST served the following files are needed on Dockerhost
 * a directory inside the webroot folder /var/www/ that contains the data served
 * a .config file inside /var/sites-enabled that will define a named VHOST
 * a WRITABLE directory inside /var/moodledata/ to provide a separate space for every Moodle instance served (not needed for any non-Moodle VHOST)

There is a web interface and a CLI tool 'deploy_vhost' that will do most of the work - all you need is to provide a matching directory in the webroot folder.

build_all.sh [nodocker]
-----------------------
<i>needs to run as superuser.</i>

This script will build all Docker images and will prepare the Docker host for running the multihost.
Use the 'nodocker' option to skip the building process for docker images

It will install the basic config file into
 * /etc/multihost.conf

The script will wait for any input and then automatically open an editor and ask to update the settings in this file as this is needed to complete the setup.

You will need to adopt the settings to these repositories on the <b>host</b> machine according to your setup:
 * sites_enabled_path	: path to the folder 'sites-enabled' that contains the server .config files which will be used by apache2/httpd in the Docker container
 * www_path		: path to the general document root for the apache2/httpd server - it will comtain subdirectories that should match the .config files in sites-enabled
 * moodledata_path	: path to the general moodledata folder - it will contain a subrirectory for every (Moodle-)server configured. It should contain a symlink to /filedir to access moodledata files. This way the cache is retained even between server restarts.
 * filedir_path		: path to a moodledata/filedir. This repository which will be available as /filedir in running Docker containers and from there can be mapped multiple times via symlink into the moodledata used by the VHOSTs in the Docker container.

After the setting have been changed/confirmed the installation will finish by calling the 'install_commands.sh' command file (see below) and finally will start the Docker multihost server.

install_commands.sh
-------------------
<i>needs to run as superuser</i>

This script will install/update the following multihost CLI commands (please see descriptions of these commands further below):
 * run_multihost - run or restart a multihost instance
 * restart_multihost - restart the Apache2/httpd server inside the Docker container to allow changes in configuration
 * enable_vhost - this command allows to enable a VHOST on the Docker multihost server
 * disable_vhost - this command will disable the settings for a given VHOST on the server - but will NOT remove the web data.
 * multihost_default - this command will make one of the existing VHOSTs teh default VHOST that is served when using the default server name or IP address. 
 * purge_moodlecache - this command removes all cached files for a given moodle VHOST.

 It will be called by the 'build_all.sh' script but can be evoked separately in case you need to update the commands.

run_multihost
-------------
<i>located at: /usr/bin/</i>

This will run TWO almost identical web servers - the only difference will be the PHP version (5.6 vs 7.1).
The first server will run on ports 80 and 443 while the second runs on ports 8080 and 8443.
Without any options 'sudo run_multihost' will run the first server with PHP 7.1 and the second with PHP 5.6.
To reverse that order issue the command with an option like so 'sudo run_multihost -p5'

This script will also create/update the /usr/local/bin/restart_multihost command (see below) accordingly.

restart_multihost
----------------
<i>located at: /usr/bin</i>

This command allows to restart both Apache2/httpd servers inside the Docker containers so to reload changes in the - shared - config files.

enable_vhost <i>servername</i>
------------------------------
<i>located at: /usr/bin</i> | <i>needs to run as superuser</i>

With this command a new VHOST with the name <i>servername</i> will be eanbled for the multihost server.

The servername must be unique and in the webroot of the multihost server needs to be a folder with the same name.
When the VHOST has been deployed the Apache2/httpd service will be restarted to activate the new VHOST. 

You then will need to change your local /etc/hosts file accordingly to access the VHOST.

purge_moodlecache <i>servername</i>
-----------------------------------
<i>located at: /usr/bin</i> | <i>needs to run as superuser</i>

Since cached data is preserved between restarts or even rebuilds of the multihost server they may be purged using this command.

It removes all cached data for the given Moodle VHOST. The data then will be recreated by the application.

default.configuration
---------------------
When running 'build_all.sh' this file will automatically be copied into the 'sites-enabled' folder.

It will be used by the 'deploy_vhost' command when creating a config file for the new VHOST and it needs to have the name 'default.configuration'.

multihost.conf
--------------
<i>located at: /etc/multihost.conf</i>

This file contains all user defined settings for the multihost. It contains the settings that were edited or confirmed when running the build_all.sh script and is needed by the commands 'run_multihost' and 'deploy_vhost' to work properly.

disable_vhost <i>servername</i>
------------------------------
<i>located at: /usr/bin</i> | <i>needs to run as superuser</i>

This command will disable a given VHOST and will remove all related settings - but not the web data itself!

uninstall_all.sh [nodocker]
---------------------------
<i>needs to run as superuser</i>

This will remove the complete multihost docker installation from the machine - but NOT any web data nor any configuration files.

Use the 'nodocker' option to keep existing Docker images.

The script will call the remove_commands.sh script.

remove_commands.sh
------------------
<i>needs to run as superuser</i>

This script will remove all multihost CLI commands.

update_all.sh [nodocker]
------------------------
<i>needs to run as superuser</i>

Use this script to easily update all installed scripts and commands. It will preserve the current settings in /etc/multihost.conf.

Use option 'nodocker' to keep the current Docker images. 

----------------
v.1.6.1