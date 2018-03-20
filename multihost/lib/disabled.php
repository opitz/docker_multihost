<?php
#------------------------------------------------------------------------------
function enable_button($vhost = false) {
	if(!$vhost) return false;
	return '<dev class="ui green mini button enable_button" id="'.$vhost.'">Enable</dev>';
}

#==============================================================================
$vhosts = scandir('/var/www');
$html = '';

$html .= "<b>Disabled VHOSTs:</b><hr>";
$html .= "<table style='width: 100%;'><tbody>";
$html .= "";
$html .= "<tr><th>&nbsp;</th><th></th></tr>";
foreach($vhosts as $vhost)
{
	if(is_dir('/var/www/'.$vhost) && !file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf') && $vhost != '.' && $vhost != '..' && $vhost != 'html') {
		$html .= '<tr><td class="column"><li>'.$vhost.'</td><th id="'.$vhost.'">'.enable_button($vhost).'</th></tr>';
	}
}
$html .= "</tbody></table>";
echo $html;
?>
