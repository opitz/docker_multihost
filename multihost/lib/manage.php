<p></p>
<p><span class="subheader">Manage VHOSTs</span></p>
<p>
You will be able to enable or disable any existing directory under '<code>/var/www</code>' as a web server with the click of a button. The web server will have the same name as the directory. To access add the name of the VHOST to the host's URL like so <code>https://host/servername</code> or simply click the appropriate button on this page.<p>

Alternatively you might want to access it using it's name. For this you will have to change your local '/etc/hosts' file and connect the name to the IP address of this server (see above). You then will be able to access the server like so: <code>https://servername</code>.
</p>
<p></p>
<p><span class="subheader">Moodle VHOSTs</span></p>
A possible <a href="http://www.moodle.org" target="new">Moodle</a> instance will automatically be detected when enabling a VHOST. This will create a repository for moodledata and a crontab entry for moodle maintenance. For enabled Moodle VHOSTs you can purge the Moodle cache by pressing the "Purge Cache" button next to it.
<p></p>
For Moodle installations on this server you may want to use the following config file as a blueprint: <a href="lib/config.txt" target=new>config.php</a>.
