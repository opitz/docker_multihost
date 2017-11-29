centos7_php56_httpd
------------------

This Dockerfile will build a httpd (Apache2) web server with PHP 5.6 on base of the latest Centos7 release.
It is identical to the 'centos7_php7_httpd' Docker image exept for the PHP version.

NOTE: This Docker image has to run with the '--privileged=true' option to make systemd work!

yum
---
The build adds the following packages to the base image:

* httpd (Apache2)
* PHP56w (epel/webtatic release)
* SSL
* systemd
* cronie
* nano (editor)

Apache setup
------------
A self signed certificate is created and stored to allow (non-secure) https connections to the server.

During build /etc/httpd/conf/httpd.conf is amended so to scan '/etc/httpd/conf/sites-enabled' directory if present.
This will be mapped from the host into the running Docker container and contains the (additional) configurations for VHOSTs.

xdebug is enabled with xdebug.idekey=PHPSTORM.

PHP setup
---------
php.ini is modified so that the allowed memory is increased from 128M to 512M.
Furthermore there is no limit for max_execution_time and max_input_time so to be able to handle extreme situations.

systemd
-------
Finally systemd is enabled.

Managing services manually
--------------------------

If you are NOT using the 'run_multihost' command you may want to start the installed services manually after you are running the Docker container like so:

 * docker exec multihost_centos7_php56_httpd systemctl start httpd
 * docker exec multihost_centos7_php56_httpd systemctl start crond

Alternatively you may run the 'run_multihost' command

