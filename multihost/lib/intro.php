<span>
	This is Docker <span class="multi">Multi</span>Host, a multi(sic!) web server based on Docker and optimized for - but not limited to - Moodle instances.
	<br>It is targeted at serving multiple virtual hosts (VHOSTs) with one server setup.
	<p>
	Please see below how to set up your own VHOSTs.<br>
	<p>
	<span class="subheader">Double Server</span>
	<p>
	Two almost identical web servers run on this host - the only difference being the PHP version and ports used:<ul>
		<li>Centos7, PHP 7.1 - ports <?php echo file_get_contents('port.txt').'/ '.file_get_contents('ssl_port.txt');?></li>
		<li>Centos7, PHP 5.6 - ports <?php echo file_get_contents('port2.txt').'/ '.file_get_contents('ssl_port2.txt');?></li>
	</ul>
	Both servers will use identical config files and serve the very same content enabled.<br>
	To see a web page with the other PHP version simply add/remove the port number as shown above to/from the URL used in the browser.
</span>