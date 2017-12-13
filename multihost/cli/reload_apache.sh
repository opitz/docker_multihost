#!/usr/bin/env bash

# script to reload the config for the apache server in Docker multihost and re-install the current crontab
# m.opitz@qmul.ac.uk | 2017-12-13
moodledata_path="/var/moodledata"

# (re-)load the crontab
#sudo crontab ${moodledata_path}/moodle_crontab
#reload web server config
sudo systemctl reload httpd
