FROM centos:latest
MAINTAINER Matthias Opitz <m.opitz@qmul.ac.uk>
# v.1.4.1 - 2018-04-04
ENV container docker
# uncomment the next 2 lines when running inside a network that requires proxy for access
#ENV http_proxy http://wwwcache.qmul.ac.uk:8080
#ENV https_proxy https://wwwcache.qmul.ac.uk:8080

# install repositories
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm && \
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

# Update the system
RUN yum -y update; yum clean all

# install deltarpm
RUN yum -y install deltarpm

# install Apache2 with PHP 7.1 and all the needed trimmings for Moodle development
RUN yum install -y \
	httpd \
	mod_\
	php71w \
	php71w-dom \
	php71w-gd \
	php71w-intl \
	php71w-ldap \
	php71w-mbstring \
	php71w-mcrypt \
	php71w-mysqli \
	php71w-opcache \
	php71w-pecl-xdebug \
	php71w-pear \
	php71w-process \
	php71w-soap \
	php71w-xmlrpc \
	ssl \
	mod_ssl \
	systemd \
	openssl \
	cronie \
	sudo \
	nano \
        vim-enchanced
 
RUN yum -y clean all

# SSL - Install self-signed certificate
RUN mkdir /etc/ssl/private; chmod -R 777 /etc/ssl/private
RUN openssl genrsa -des3 -passout pass:x -out /etc/ssl/private/apache.pass.key 2048 && \
    openssl rsa -passin pass:x -in /etc/ssl/private/apache.pass.key -out /etc/ssl/private/apache.key && \
    rm /etc/ssl/private/apache.pass.key && \
    openssl req -new -key /etc/ssl/private/apache.key -out /etc/ssl/certs/apache.csr \
        -subj "/C=UK/ST=London/L=London/O=QMUL/OU=ITS/CN=m.opitz@qmul.ac.uk" && \
    openssl x509 -req -days 365 -in /etc/ssl/certs/apache.csr -signkey /etc/ssl/private/apache.key -out /etc/ssl/certs/apache.crt

# add sites-enabled to httpd.conf
RUN echo "IncludeOptional sites-enabled/*conf" >> /etc/httpd/conf/httpd.conf

# remove the default ssl configuration
RUN mv /etc/httpd/conf.d/ssl.conf /etc/httpd/conf.d/ssl.conf.bak

# setup apache as sudoer - so we can use a web interface to config the server
RUN echo "apache ALL=(ALL)  NOPASSWD: ALL" >> /etc/sudoers

# setup xdebug
#RUN rm /etc/php.d/15-xdebug.ini && \
RUN echo echo "xdebug.remote_enable=1" >> /etc/php.d/xdebug.ini && \
	echo "xdebug.remote_autostart=off" >> /etc/php.d/xdebug.ini && \
	echo "xdebug.remote_host=172.17.0.1" >> /etc/php.d/xdebug.ini && \
	echo "xdebug.idekey=PHPSTORM" >> /etc/php.d/xdebug.ini \
        sed -i '2s/^/;/' /etc/php.d/xdebug.ini


# create directory for moodle data - if present on host it may be mapped to here ( -v /path/to/moodedata/on/host:/var/moodledata )
RUN mkdir /var/moodledata && \
	chmod -R 777 /var/moodledata

# set PHP max execution time
RUN phpini=$(php -i|grep 'Loaded Configuration'| awk '{print $5}'); \
	sed "s/^;date.timezone =$/date.timezone = Europe\/London /" $phpini; \
	sed -i "s/^max_execution_time = 30$/max_execution_time = 0 /" $phpini; \
	sed -i "s/^max_input_time = 60$/max_input_time = -1 /" $phpini

# set PHP memory limit
RUN phpini=$(php -i|grep 'Loaded Configuration'| awk '{print $5}'); \
	sed -i "s/^memory_limit = 128M$/memory_limit = 1G /" $phpini

# compile locale definitions to australian english required for unit testing
#RUN localedef -c -i en_AU -f --quiet UTF-8 en_AU.UTF-8

# install systemd - the resulting image needs to run with --privileged=true !!

RUN (cd /lib/systemd/system/sysinit.target.wants/; for i in *; do [ $i == \
	systemd-tmpfiles-setup.service ] || rm -f $i; done); \
	rm -f /lib/systemd/system/multi-user.target.wants/*; \
	rm -f /etc/systemd/system/*.wants/*;\
	rm -f /lib/systemd/system/local-fs.target.wants/*; \
	rm -f /lib/systemd/system/sockets.target.wants/*udev*; \
	rm -f /lib/systemd/system/sockets.target.wants/*initctl*; \
	rm -f /lib/systemd/system/basic.target.wants/*;\
	rm -f /lib/systemd/system/anaconda.target.wants/*;
VOLUME [ "/sys/fs/cgroup" ]
CMD ["/usr/sbin/init"]
