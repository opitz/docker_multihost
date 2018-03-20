<span>
	<h2>What is this?</h2>
	This is Docker <span class="multi"><b>multi</b>host</span>, a multi web server based on Docker and optimized for - but not limited to - Moodle instances. It is targeted at serving multiple virtual hosts (VHOSTs) with (currently) two web server setups. This will allow to compare the same code running in different environments (e.g. with PHP 5.6 and PHP 7.1). <br>
	For enabled Moodle VHOSTs you can purge the Moodle cache using the "Purge Cache" button.
	
	<h2>Double Server</h2>
	Two almost identical web servers run on this host - the only difference being the PHP version and ports used:<ul>
		<li>Centos7, PHP 7.1 - ports <?php echo file_get_contents('port.txt').'/ '.file_get_contents('ssl_port.txt');?></li>
		<li>Centos7, PHP 5.6 - ports <?php echo file_get_contents('port2.txt').'/ '.file_get_contents('ssl_port2.txt');?></li>
	</ul>
	Both servers will use the same config files and serve the very same enabled content.
</span>