<?php
$version = '0.x';
$date = '2017-12-08';

#------------------------------------------------------------------------------
function enable_vhost($vhost = false){
	if(!$vhost) {
		echo "<dev class='alert'>No vhost given - please investigate!</dev>";
		return false;
	}

	if(array_key_exists('no_moodle',$_POST)){
	  	$cmd = "cli/enable_vhost.sh $vhost no_moodle";
	} else {
		$cmd = "cli/enable_vhost.sh $vhost";
	}
	shell_exec($cmd);
}

#------------------------------------------------------------------------------
function disable_vhost($vhost = false){
	if(!$vhost) {
		echo "<dev class='alert'>No vhost given - please investigate!</dev>";
		return false;
	}
	$cmd = "cli/disable_vhost.sh $vhost";
	shell_exec($cmd);
}

#------------------------------------------------------------------------------
function make_default($vhost = false){
	if(!$vhost) {
		echo "<dev class='alert'>No vhost given - please investigate!</dev>";
		return false;
	}
	$cmd = "sudo cli/make_default.sh $vhost";
	shell_exec($cmd);
}

#------------------------------------------------------------------------------
function purge_moodlecache($vhost = false){
	if(!$vhost) {
		echo "<dev class='alert'>No vhost given - please investigate!</dev>";
		return false;
	}
	$cmd = "sudo cli/purge_moodlecache.sh $vhost";
	shell_exec($cmd);
}

#------------------------------------------------------------------------------
function enable_button($vhost = false) {
	if(!$vhost) return false;

	return '<form method="post">
	<input type = "hidden" name = "vhost" value = "'.$vhost.'" />
	no moodle<input type = "checkbox" name = "no_moodle" value = "1" />
    <input class="button" type="submit" name="enable" id="'.$vhost.'" value="Enable" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function default_button($vhost = false) {
	if(!$vhost) return false;

	return '<form method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="default" id="'.$vhost.'" value="Make default" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function disable_button($vhost = false) {
	if(!$vhost) return false;
	if($vhost == 'multihost') return 'mandatory';

	return '<form method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="disable" id="'.$vhost.'" value="Disable" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function cache_button($vhost = false) {
	if(!$vhost) return false;
	if(!file_exists('/var/moodledata/'.$vhost.'/cache')) return "cache purged";

	return '<form method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="purge_moodlecache" id="'.$vhost.'" value="Purge Cache" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
if(array_key_exists('enable',$_POST)){
   enable_vhost($_POST['vhost']);
}

if(array_key_exists('default',$_POST)){
   make_default($_POST['vhost']);
}

if(array_key_exists('disable',$_POST)){
   disable_vhost($_POST['vhost']);
}

if(array_key_exists('purge_moodlecache',$_POST)){
   purge_moodlecache($_POST['vhost']);
}
#------------------------------------------------------------------------------
#------------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>

<style>
.header { font-size:28px; color:#F60; }
.subheader { font-size:18px; font-weight: bold; color:#3456A3;}
.footer { font-size:12px; font-style: italic; }
.alert { color:red; }
.button { width:100px; }
.is_default { width:100px; font-weight: bold; }
.column { width:240px; }
.disable { text-align:center; }
.top_table {  }
.left_column { width:60%; vertical-align:top; }
.right_column { vertical-align:top; background-color: #EEE; text-indent: 10px; padding-top: 10px; }
.gap { width:20px; }
.note { color:#FF6600; font-weight:bold; }
</style>

<title>Docker multihost</title>
</head>

<body>
<span class="header">Docker <b>multi</b>host</span>
<hr>
<table class="top_table">
	<tr>
		<td class="left_column">
			This is Docker <b>multi</b>host, a web server based on Docker and optimized for Moodle instances.<br>
			It is targeted at serving multiple virtual hosts (VHOSTs) with a single web server instance.
			<p>
			This is the internal 'multihost' webpage which on a new installation serves as the default web content as well.<br>
			Please see below how to set up your own VHOSTs and how to make one of them the default content served.<br>
			The default content will be shown when using the IP address or the generic DNS name of the server.
			<p>
			<span class="note">Please note:</span><br>
			If you want to access this help page after you made another VHOST the default one please add 'multihost'<br>
			to your local /etc/hosts/ file and connect it to the IP address of the machine this server is running on.
		</td>
		<td class="gap"></td>
		<td class="right_column">
			<span class="subheader">Server details</span>
			<p>
			<?php 
			echo "Docker container: <b>".php_uname('n')."</b><p>";
			echo "PHP version: <b>".phpversion()."</b><p>";
			if(file_exists('ip.txt')) { 
				echo "IP address: <b>" . file_get_contents('ip.txt') . "</b><p>";
			} else {
				echo "<span class='alert'>No IP address found - this is weird...!</span>";
			}
			if($xdebug=phpversion('xdebug')) echo"<b>xdebug $xdebug</b> is installed<p>";
			?>
		</td>
	</tr>
</table>

<hr>

<p><span class="subheader">Manage VHOSTs</span></p>
<p>
Here you will be able to enable or disable any existing web server directory under '/var/www' with the click of a button.<br>
To access it using it's name you will have to change your local '/etc/hosts' file and connect the name to the IP address of this server (see above).
</p>
<p>
<span class="note">Please note:</span> When you check the 'no moodle' box before enabling a VHOST it will have no folder for moodledata and no crontab entry for moodle maintenance which are both only useful for Moodle instances.
</p>
<p>
<table><tr>
	<td valign="top">
		<b>Enabled VHOSTs:</b><ul>
<?php
			$vhosts = scandir('/var/www');
			foreach($vhosts as $vhost)
			{
				if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
					echo'<table><tr><td class="column">';
					if(file_exists('/var/moodledata/'.$vhost)) {
						if(realpath('/var/www/html') == '/var/www/'.$vhost){
							echo '<li><b>'.$vhost.'</b></li></td><td class="is_default">>>is default<<</td><td class="disable"></td><td>'.cache_button($vhost);
						} else {
							echo '<li>'.$vhost.'</li></td><td class="default">'.default_button($vhost).'</td><td class="disable">'.disable_button($vhost).'</td><td>'.cache_button($vhost);
						}
					} else {
						if(realpath('/var/www/html') == '/var/www/'.$vhost){
							echo '<li><b>'.$vhost.'</b></li></td><td class="is_default">>>is default<<</td><td class="disable"></td><td>';
						} else {
							echo '<li>'.$vhost.'</li></td><td class="default">'.default_button($vhost).'</td><td class="disable">'.disable_button($vhost).'</td><td>';
						}
					}
					echo '</td></tr></table>';
				} 
			}	
?>
		</ul>
	</td>
	<td width=20></td>
	<td valign="top">
		<b>Disabled VHOSTs:</b><ul>
<?php
		foreach($vhosts as $vhost)
		{
			if(is_dir('/var/www/'.$vhost) && !file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf') && $vhost != '.' && $vhost != '..' && $vhost != 'html') {
				echo '<table><tr><td class="column"><li>'.$vhost.'</li></td><td class="enable">'.enable_button($vhost).'</td></tr></table>';
			}
		}	
?>
		</ul>
	</td>
</tr></table>
</p>
<hr>
<p>
	<span class="subheader">New multihost command line commands</span>
	<br>
	During the setup of the multihost server you installed several multihost CLI commands that need to run as superuser (sudo <i>command</i>):
	<ul>
		<li><b>run_multihost</b> - (Re-)start the multihost server. By default it will run with Centos7 and PHP7.</li> 
		<li><b>restart_multihost</b> - Restart the web server inside the Docker container to reload new config.<br>This will automatically be issued when enabling or disabling VHOSTs.</li> 
		<li><b>enable_vhost <i>servername</i></b> - Enable a VHOST (see below).</li> 
		<li><b>disable_vhost <i>servername</i></b> - Disable an existing VHOST and all it's settings - but <b>NOT</b> removing the web data.</li> 
		<li><b>multihost_default <i>servername</i></b> - Promoting one of the existing(!) VHOSTs to default web server.</li> 
		<li><b>purge_moodlecache <i>servername</i></b> - Removing any cached moodledata for the given servername.</li> 
	</ul>
</p>
<p>
	<span class="subheader">Enable VHOST</span>
	<br>
	To enable a VHOST using the CLI do these steps in a terminal:
	<ul>
		<li>If not already present: add or git clone a webroot folder with the name of the new server into your basic webroot folder (e.g. /var/www/<i>servername</i>)</li>
		<li>Issue 'sudo enable_vhost <i>servername</i>'</li>
		<li>Add an entry for <i>servername</i> into your local(!) /etc/hosts and link it to the IP address of this server ( <?php echo file_get_contents('ip.txt');?>).</li>
	</ul>
</p>
<p>
	<span class="subheader">Make a VHOST the default web content</span>
	<br>
	To make one of the existing VHOSTs the default one simply issue
	<ul>
		<li>sudo multihost_default <i>servername</i></li>
	</ul>

</p>
In the above cases please replace <i>servername</i> with the actual server name.

<hr>
<?php
echo "<span class='footer'>v.$version | $date</span>";
?>

</body>