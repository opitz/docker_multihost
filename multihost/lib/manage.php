<p><span class="subheader">Manage VHOSTs</span></p>
<p>
Here you will be able to enable or disable any existing directory under '<code>/var/www</code>' as a web server with the click of a button.<br>
The web server will have the same name as the directory.<br>
To access add the name of the VHOST to the host's URL like so <code>https://host/servername</code> and you should be good to go.<br>

Alternatively you might want to access it using it's name. For this you will have to change your local '/etc/hosts' file and connect the name to the IP address of this server (see above). You then will be able to access the server like so: <code>https://servername</code>.
</p>
<p>
<span class="note">Please note:</span> A possible Moodle instance will automatically be detected when enabling a VHOST so a repository for moodledata and a crontab entry for moodle maintenance will be created. For enabled Moodle VHOSTs you can purge the Moodle cache by pressing the "Purge Cache" button next to it.<br>
For Moodle installations on this server you may want to use the following config file as a blueprint: <a href="lib/config.txt" target=new>config.php</a>.
