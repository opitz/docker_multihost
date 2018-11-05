FROM ubuntu:latest
MAINTAINER Matthias Opitz (m.opitz@qmul.ac.uk)
# v.1.1 - 2017-11-27

ENV container docker
ENV http_proxy http://wwwcache.qmul.ac.uk:8080
ENV https_proxy https://wwwcache.qmul.ac.uk:8080

RUN apt-get update
RUN apt-get -y install nano apache2 software-properties-common locales openssl

# Ensure UTF-8
RUN locale-gen en_US.UTF-8
ENV LANG       en_US.UTF-8
ENV LC_ALL     en_US.UTF-8

RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get -y install php7.0 php7.0-mysqli php7.0-xdebug php7.0-xml
RUN apt-get -y --allow-unauthenticated install php7.0-mcrypt php7.0-curl php7.0-zip php7.0-gd php7.0-mbstring
RUN apt-get -y --allow-unauthenticated install php7.0-xmlrpc php7.0-soap php7.0-intl
RUN apt-get install -y cron

RUN a2dismod php5.6
RUN a2enmod php7.0
RUN a2enmod rewrite
RUN a2enmod ssl

# SSL - Install self-signed certificate
RUN openssl genrsa -des3 -passout pass:x -out /etc/ssl/private/apache.pass.key 2048 && \
    openssl rsa -passin pass:x -in /etc/ssl/private/apache.pass.key -out /etc/ssl/private/apache.key && \
    rm /etc/ssl/private/apache.pass.key && \
    openssl req -new -key /etc/ssl/private/apache.key -out /etc/ssl/certs/apache.csr \
        -subj "/C=UK/ST=London/L=London/O=QMUL/OU=ITS/CN=m.opitz@qmul.ac.uk" && \
    openssl x509 -req -days 365 -in /etc/ssl/certs/apache.csr -signkey /etc/ssl/private/apache.key -out /etc/ssl/certs/apache.crt

# enabling xdebug
RUN echo "zend_extension=xdebug.so" >> /etc/php/7.0/apache2/php.ini && \
	echo "xdebug.remote_enable=1" >> /etc/php/7.0/apache2/php.ini && \
	echo "xdebug.remote_autostart=off" >> /etc/php/7.0/apache2/php.ini && \
	echo "xdebug.remote_host=172.17.0.1" >> /etc/php/7.0/apache2/php.ini && \
	echo "xdebug.idekey=PHPSTORM" >> /etc/php/7.0/apache2/php.ini

RUN mkdir /var/moodledata
RUN chmod -R 777 /var/moodledata

# set PHP max execution time
RUN phpini=$(php -i|grep 'Loaded Configuration'| awk '{print $5}'); \
	sed "s/^;date.timezone =$/date.timezone = Europe\/London /" $phpini; \
	sed -i "s/^max_execution_time = 30$/max_execution_time = 0 /" $phpini; \
	sed -i "s/^max_input_time = 60$/max_input_time = -1 /" $phpini

# set PHP memory limit
RUN phpini=$(php -i|grep 'Loaded Configuration'| awk '{print $5}'); \
	sed -i "s/^memory_limit = 128M$/memory_limit = 512M /" $phpini

RUN service cron start
RUN service apache2 start



