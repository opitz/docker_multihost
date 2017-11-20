!/usr/bin/env bash
# script to (re-)install all scripts/commands for multihost
echo 'install multihost commands v.1.0'

if [ ! -f run_multihost ]
	then
	echo "Could not find 'run_multihost' command - skipping!"
else
	sudo cp run_multihost /usr/local/bin/run_multihost
	sudo chmod  777 /usr/local/bin/run_multihost
fi

if [ ! -f restart_multihost ]
        then
        echo "Could not find 'restart_multihost' command - skipping!"
else
	sudo cp restart_multihost /usr/local/bin/restart_multihost
	sudo chmod 777 /usr/local/bin/restart_multihost
fi

if [ ! -f deploy_vhost ]
        then
        echo "Could not find 'deploy_host' command - skipping!"
else
	sudo cp deploy_vhost /usr/local/bin/deploy_vhost
	sudo chmod  777 /usr/local/bin/deploy_vhost
fi
echo "--------------------------------"
echo Done!
echo ' '

