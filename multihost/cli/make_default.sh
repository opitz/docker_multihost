#!/usr/bin/env bash

# script to make the given VHOST the default server content
# m.opitz@qmul.ac.uk | 2017-12-07
if [ ! $1 ]
	then 
	exit 1
fi
servername=$1
www_path="/var/www"
if [ ! -d ${www_path}/${servername} ]
	then
	echo "No server found at ${www_path}/${servername} - aborting!"
	exit 1
fi

rm -rf ${www_path}/html 2>/dev/null
ln -s ${servername} ${www_path}/html
