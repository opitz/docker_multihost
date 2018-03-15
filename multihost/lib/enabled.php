<b>Enabled VHOSTs:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="small_text">(Clicking a button will open the website in a new window/tab)</span>
<table class="ui table">
	<tbody>
		<?php
		$vhosts = scandir('/var/www');
		$php_version1 = '7.1';
		$php_version2 = '5.6';
		$port1 = 443;
		$port2 = 8443;
		echo "<tr><th align=center>PHP $php_version1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align=center>PHP $php_version2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th></th><th></th></tr>";
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
				echo "<tr><td>";
				if(file_exists('/var/moodledata/'.$vhost)) {
					echo server_button($vhost, $php_version1, $port1).'</td><td>'.server_button($vhost, $php_version2, $port2).'</td><td>'.cache_button($vhost).'</td><td>'.disable_button($vhost);
				} else {
					echo server_button($vhost, $php_version1, $port1).'</td><td>'.server_button($vhost, $php_version2, $port2).'</td><td></td><td>'.disable_button($vhost);
				}
				echo "</td></tr>";
			}
		}
		?>
	</tbody>
</table>
