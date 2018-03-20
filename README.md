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
* to update an existing installation cd in to this directory, run 'git pull' followed by 'sudo ./update_all.sh'.
* point your browser to the IP address of the server (if Docker multihost is running locally you may just use 'localhost').

The multihost web interface
---------------------------
After a sucessful build of the server (see below) you should be able to enter https://<host-IP-or-DNS-name> to see the main console of Docker multihost.

To make changes to teh settings (e.g. enabling or disabling VHOSTs running on this server) you will have to log in.
After a the first installation there is only one user 'admin' with the password 'admin'. You may add new users and change the password for every user using the 'Edit User' button when logged in. As of now there all users are created equal and are able to change everyone's passwords.
All users and their (hashed) passwords are stored in /etc/multihost.user on the host machine and is bound into each Docker container. To reset the user settings simply remove that file and run the update_all.sh script. This will install a new users file with user 'admin' and password 'admin'.
When logged in you may anable or disable any VHOST with the click of a button.

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

Apart from the web interface there is a CLI tool 'deploy_vhost' that will do most of the work - all you need is to provide a matching directory in the webroot folder.

PLEASE NOTE: In the current version the Dockerfiles for the Ubuntu images are not built as they are not used. To build them please uncomment the corresponding lines in the build_all.sh script (see below) and run build_all.sh (again).


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