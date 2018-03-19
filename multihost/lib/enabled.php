<?php
//include('functions.php');
#------------------------------------------------------------------------------
function server_button($vhost = false, $php_version = false, $port = false) {
	if(!$vhost) return false;
	if(!$php_version) 
		return false;
	return '<button class="ui blue mini button server_button" id="server_'.$vhost.'" value="https://'.$_SERVER['HTTP_HOST'].':'.$port.'/'.$vhost.'">'.$vhost.'</button>';
}

#------------------------------------------------------------------------------
function cache_button($vhost = false) {
	if(!$vhost) return false;
	if(!file_exists('/var/moodledata/'.$vhost.'/sessions')) 
		return "<button class='ui mini disabled button cached_button' id='$vhost'>Cache purged</button>";
	return '<dev class="ui mini button cache_button" id="'.$vhost.'">Purge Cache</dev>';
}

#------------------------------------------------------------------------------
function disable_button($vhost = false) {
	if(!$vhost) return false;
	if(realpath('/var/www/html') == '/var/www/'.$vhost) 
		return '';
	session_start();
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
	    // last request was more than 30 minutes ago
	    session_unset();     // unset $_SESSION variable for the run-time 
	    session_destroy();   // destroy session data in storage
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	if(isset($_SESSION['logged_in'])) 
		return '<dev style="display: show;" class="admin ui red mini button disable_button" id="'.$vhost.'">Disable</dev>';
	else
		return '<dev style="display: none;" class="admin ui red mini button disable_button" id="'.$vhost.'">Disable</dev>';
}

#==============================================================================
$vhosts = scandir('/var/www');
$php_version1 = '7.1';
$php_version2 = '5.6';
$port1 = file_get_contents('ssl_port.txt');
$port2 = file_get_contents('ssl_port2.txt');
$html = '';

$html .= "<b>Enabled VHOSTs:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='small_text'>(Clicking a button will open the website in a new window/tab)</span>";
$html .= "<hr>";
$html .= "<table class='ui0 table0' style='width: 100%;'>";
$html .= "<tbody>";
$html .= "<tr><th align=center>PHP $php_version1</th><th>PHP $php_version2</th><th></th><th></th></tr>";

foreach($vhosts as $vhost)
{
	if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
		$html .= "<tr><th>";
		if(file_exists('/var/moodledata/'.$vhost)) {
			$html .= server_button($vhost, $php_version1, $port1).'</th><th>'.server_button($vhost, $php_version2, $port2).'</th><th>'.cache_button($vhost).'</th><th>'.disable_button($vhost);
		} else {
			$html .= server_button($vhost, $php_version1, $port1).'</th><th>'.server_button($vhost, $php_version2, $port2).'</th><th></th><th>'.disable_button($vhost);
		}
		$html .= "</th></tr>";
	}
}
$html .= "</tbody></table>";

echo $html;
?>
