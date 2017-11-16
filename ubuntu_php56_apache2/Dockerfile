FROM ubuntu:latest
MAINTAINER Matthias Opitz (m.opitz@qmul.ac.uk)
# v.1.0 - 2017-10-16

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
RUN apt-get -y install php5.6 php7.0 php5.6-mysqli php5.6-xdebug php5.6-xml
RUN apt-get -y --allow-unauthenticated install php5.6-mcrypt php5.6-curl php5.6-zip php5.6-gd php5.6-mbstring
RUN apt-get -y --allow-unauthenticated install php5.6-xmlrpc php5.6-soap php5.6-intl
#RUN apt-get -y --allow-unauthenticated install php5.6-mcrypt php5.6-xml php5.6-curl php5.6-zip php5.6-gd php5.6-mbstring php5.6-xmlrpc php5.6-soap php5.6-intl

RUN a2dismod php7.0
RUN a2enmod php5.6
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
RUN echo "zend_extension=xdebug.so" >> /etc/php/5.6/apache2/php.ini && \
	echo "xdebug.remote_enable=1" >> /etc/php/5.6/apache2/php.ini && \
	echo "xdebug.remote_autostart=off" >> /etc/php/5.6/apache2/php.ini && \
	echo "xdebug.remote_host=172.17.0.1" >> /etc/php/5.6/apache2/php.ini && \
	echo "xdebug.idekey=PHPSTORM" >> /etc/php/5.6/apache2/php.ini

RUN mkdir /var/moodledata
RUN chmod -R 777 /var/moodledata

RUN service apache2 start



