<span class="subheader">Enable VHOST using the CLI</span>
<br>
To enable a VHOST using the CLI do these steps in a terminal:
<ul>
	<li>If not already present: add or git clone a webroot folder with the name of the new server into the basic webroot folder (e.g. /var/www/<i>servername</i>)</li>
	<li>Issue 'sudo enable_vhost <i>servername</i>'</li>
	<li>You then may access the server under <code>https://hostname/servername</code>.</li>
	<li>You may alternatively change your local <code>/etc/hosts</code> file and relate any <i>servername</i> to the IP address of this server ( <?php echo file_get_contents('ip.txt');?>) to allow access via <code>https://servername</code>.</li>

</ul>
