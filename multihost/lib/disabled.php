<b>Disabled VHOSTs:</b>
<hr>
<table class="ui0 table0" style="width: 100%;">
	<tbody>
		<?php
		echo "<tr><th>&nbsp;</th><th></th></tr>";
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && !file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf') && $vhost != '.' && $vhost != '..' && $vhost != 'html') {
				echo '<tr><td class="column"><li>'.$vhost.'</td><th class="enable_button" id="'.$vhost.'">'.enable_button($vhost).'</th></tr>';
			}
		}
		?>
	</tbody>
</table>
