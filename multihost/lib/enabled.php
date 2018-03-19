<b>Enabled VHOSTs:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="small_text">(Clicking a button will open the website in a new window/tab)</span>
<hr>
<table class="ui0 table0" border="0" style="width: 100%;">
	<tbody>
		<?php
		$vhosts = scandir('/var/www');
		$php_version1 = '7.1';
		$php_version2 = '5.6';
		$port1 = file_get_contents('ssl_port.txt');
		$port2 = file_get_contents('ssl_port2.txt');
		echo "<tr><th align=center>PHP $php_version1</th><th>PHP $php_version2</th><th></th><th></th></tr>";
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
				echo "<tr><th>";
				if(file_exists('/var/moodledata/'.$vhost)) {
					echo server_button($vhost, $php_version1, $port1).'</th><th>'.server_button($vhost, $php_version2, $port2).'</th><th>'.cache_button($vhost).'</th><th>'.disable_button($vhost);
				} else {
					echo server_button($vhost, $php_version1, $port1).'</th><th>'.server_button($vhost, $php_version2, $port2).'</th><th></th><th>'.disable_button($vhost);
				}
				echo "</th></tr>";
			}
		}
		?>
	</tbody>
</table>
