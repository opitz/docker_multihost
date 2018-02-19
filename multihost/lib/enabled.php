<b>Enabled VHOSTs:</b>
<table class="ui table">
	<tbody>
		<?php
		$vhosts = scandir('/var/www');
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
				echo "<tr><td>";
				if(file_exists('/var/moodledata/'.$vhost)) {
					echo '<a href="'.$vhost.'" target=new>'.$vhost.'</a> (Moodle) </td><td>'.disable_button($vhost).'</td><td>'.cache_button($vhost);
				} else {
					echo '<a href="'.$vhost.'" target=new>'.$vhost.'</a> </td><td>'.disable_button($vhost);
				}
				echo "</td></tr>";
			}
		}
		?>
	</tbody>
</table>
