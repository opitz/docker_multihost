This repository contains the Dockerfiles to create the following Docker images
 * centos7_php7_httpd
 * centos7_php56_httpd
 * ubuntu_php7_apache2
 * ubuntu_php56_apache2

run_multihost
-------------

It also containes a bash script to run a Docker container. It should idally placed in your PATH (e.g. /usr/sbin/run_multihost) and needs to be executable
You can run a Docker image  with one of the following uses:
 * run_multihost 		: centos7_php7_httpd (default)
 * run_multihost -ou 		: ubuntu_php7_apache2
 * run_multihost -p5 		: centos7_php56_httpd
 * run_multihost -ou -p5 	: ubuntu_php56_apache2 

You will need to adopt the settings to the host repositories to the situation on the Docker server:
 * sites_enabled_path	: path to the folder that contains the server .config files that will be used by apache2/httpd in the Docker container
 * www_path				: path to the general document root of the apache2/httpd server - it will comtain subdirectories that should match the .config files in sites-enabld
 * moodledata_path		: path to the general moodledata folder - it will contain a subrirectory for every (Moodle-)server configured. It should contain a symlink to /filedir to access moodledata files. This way the cache is retained even between server restarts.
 * filedir_path			: path to a moodledata/filedir. This repository which will be available as /filedir in running Docker comntainers and from there can be mapped multiple times via symlink into the moodledata used by the VHOSTs in the Docker container.

