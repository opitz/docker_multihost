#!/usr/bin/env bash

# script to purge the moodledata cache for a given VHOST
# m.opitz@qmul.ac.uk | 2017-12-08
if [ ! $1 ]
	then
	exit 1
fi

moodledata_path='/var/moodledata'
servername=$1

if [ -d ${moodledata_path}/${servername} ]
	sudo rm -r ${moodledata_path}/${servername}/cache >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/filter >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/localcache >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/lock >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/muc >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/sessions >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/temp >/dev/null 2>/dev/null
	sudo rm -r ${moodledata_path}/${servername}/trashdir >/dev/null 2>/dev/null
fi
