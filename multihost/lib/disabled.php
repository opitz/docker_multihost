<b>Disabled VHOSTs:</b>
<table class="ui table">
	<tbody>
		<?php
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && !file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf') && $vhost != '.' && $vhost != '..' && $vhost != 'html') {
				echo '<tr><td class="column"><li>'.$vhost.'</td><td class="enable_button" id="'.$vhost.'">'.enable_button($vhost).'</td></tr>';
			}
		}
		?>
	</tbody>
</table>
