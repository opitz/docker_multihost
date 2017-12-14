<?php
$version = '1.7';
$date = '2017-12-13';

#------------------------------------------------------------------------------
function enable_vhost($vhost = false){
	if(!$vhost) {
		echo "<dev class='alert'>No vhost given - please investigate!</dev>";
		return false;
	}
	$cmd = "cli/enable_vhost.sh $vhost";
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
function reload_apache(){
	$cmd = "sudo cli/reload_apache.sh";
	shell_exec($cmd);
}

#------------------------------------------------------------------------------
function enable_button($vhost = false) {
	if(!$vhost) return false;

	return '<form method="post">
	<input type = "hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="enable" id="'.$vhost.'" value="Enable" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function default_button($vhost = false) {
	if(!$vhost) return false;
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '>> default <<';

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
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '';

	return '<form method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="disable" id="'.$vhost.'" value="Disable" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function cache_button($vhost = false) {
	if(!$vhost) return false;
	if(!file_exists('/var/moodledata/'.$vhost.'/sessions')) return "cache purged";

	return '<form method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="purge_moodlecache" id="'.$vhost.'" value="Purge Cache" /><br/>
</form>
';
}

#------------------------------------------------------------------------------
function reload_button() {
	return '<form method="post">
    <input class="button large" type="submit" name="reload_apache" id="'.$vhost.'" value="Reload Apache Configuration" /><br/>
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

if(array_key_exists('reload_apache',$_POST)){
   reload_apache();
}

#------------------------------------------------------------------------------
#------------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>

<style>
.header { font-size:28px; color:#3456A3; }
.subheader { font-size:18px; font-weight: bold; color:#3456A3;}
.footer { font-size:12px; font-style: italic; }
.alert { color:red; }
.button { width:100px; }
.large { width:200px; }
.is_default { width:100px; font-weight: bold; }
.column { width:240px; }
.default_button { text-align:center; font-weight:bold; }
.enable_button { text-align:center; }
.disable_button { text-align:center; }
.cache_button { text-align:center; }
.top_table {  }
.left_column { width:50%; vertical-align:top; }
.right_column { vertical-align:top; background-color: #EEE; padding-top: 10px; padding-left: 10px; }
.gap { width:20px; }
.note { color:#FF6600; font-weight:bold; }
.multi { color:#3456A3; font-weight:bold; }
</style>

<title>Docker multihost</title>
</head>

<body>
<span class="header">Docker <span class="multi">multi</span>host</span>
<hr>
<table class="top_table">
	<tr>
		<td class="left_column">
			This is Docker <span class="multi">multi</span>host, a double(!) web server based on Docker and optimized for - but not limited to - Moodle instances.
			<br>It is targeted at serving multiple virtual hosts (VHOSTs) with one server setup.
			<p>
			This is the internal 'multihost' web page which on new installations serves as the default web content.<br>
			Please see below how to set up your own VHOSTs and how to make one of them the default content served.<br>
			The default content will be shown when using the IP address or the generic DNS name of the server.
			<p>
			<span class="subheader">Double Server</span>
			<p>
			Two almost identical servers run on this host - the only difference being the PHP version and ports used:<ul>
				<li>Centos7, PHP 7.1 - ports <?php echo file_get_contents('port.txt').'/ '.file_get_contents('ssl_port.txt');?></li>
				<li>Centos7, PHP 5.6 - ports <?php echo file_get_contents('port2.txt').'/ '.file_get_contents('ssl_port2.txt');?></li>
			</ul>
			Both servers will use identical config files and serve the very same content.<br>
			To see a web page with the other PHP version simply add/remove the port number to/from the URL used in the browser.

			<p>
			<span class="note">Please note:</span><br>
			If you want to access this help page after you made another VHOST the default one please add 'multihost'<br>
			to your local /etc/hosts/ file and connect it to the IP address of the machine this server is running on (see right).
		</td>
		<td class="gap"></td>
		<td class="right_column">
			<span class="subheader">Server details</span>
			<p>
			<?php 
			echo "Docker container: <b>".php_uname('n')."</b>";
			if(file_exists('created'.$_SERVER['SERVER_PORT'].'.txt')) { 
				echo "<br> &nbsp;&nbsp;&nbsp;(Image created: " . file_get_contents('created'.$_SERVER['SERVER_PORT'].'.txt') . ")";
			}
			echo "<p>";
			if(file_exists('ip.txt')) { 
				echo "IP address: <b>" . file_get_contents('ip.txt').': '.$_SERVER['SERVER_PORT'] . "</b>";
			} else {
				echo "<span class='alert'>No IP address found - this is weird...!</span>";
			}
			echo "<p>";
			echo "PHP version: <b>".phpversion()."</b>";
			echo "<p>";
			if($xdebug=phpversion('xdebug')) echo"<b>xdebug $xdebug</b> is installed<p>";
			echo "<span class='note'>Please note:</span><br>If you have enabled a new VHOST through the web interface of one webserver the configuration for the other web server is not automatically reloaded. In case as a consequence you land on this page instead on the page of your selected VHOST please reload the Apache configuration to address this issue wth the button below.";
			echo reload_button();
			echo "<p>";
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
<span class="note">Please note:</span> When enabling a VHOST it will detect if it is a Moodle instance and will create a folder for moodledata and a crontab entry for moodle maintenance automatically.<br>
For enabled Moolde VHOSTs you can purge the Moodle cache by pressing the "Purge Cache" button next to it.
</p>
<p>
<table>
	<tr>
		<td valign="top">
			<b>Enabled VHOSTs:</b>
			<table>
				<tr>
					<td class="column"></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<ul>
	<?php
				$vhosts = scandir('/var/www');
				foreach($vhosts as $vhost)
				{
					if(is_dir('/var/www/'.$vhost) && file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf')) {
						echo'<tr><td><li>';
						if(file_exists('/var/moodledata/'.$vhost)) {
							echo '<a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$vhost.':'.$_SERVER['SERVER_PORT'].'" target=new>'.$vhost.'</a> (Moodle)</li></td><td class="default_button">'.default_button($vhost).'</td><td class="disable_button">'.disable_button($vhost).'</td><td class="cache_button">'.cache_button($vhost).'</td>';
						} else {
							echo '<a href="'.$_SERVER['REQUEST_SCHEME'].'://'.$vhost.':'.$_SERVER['SERVER_PORT'].'" target=new>'.$vhost.'</a></li></td><td class="default_button">'.default_button($vhost).'</td><td class="disable_button">'.disable_button($vhost).'</td><td></td>';
						}
						echo '</tr>';
					} 
				}
	?>
				</ul>
			</table>
		</td>
		<td width=20></td>
		<td valign="top">
			<b>Disabled VHOSTs:</b><ul>
			<table>
				<tr>
					<td class="column"></td>
					<td></td>
				</tr>
				<ul>
	<?php
			foreach($vhosts as $vhost)
			{
				if(is_dir('/var/www/'.$vhost) && !file_exists('/etc/httpd/sites-enabled/'.$vhost.'.conf') && $vhost != '.' && $vhost != '..' && $vhost != 'html') {
					echo '<tr><td class="column"><li>'.$vhost.'</li></td><td class="enable_button">'.enable_button($vhost).'</td></tr>';
				}
			}	
	?>
				</ul>
			</table>
		</td>
	</tr>
</table>
</p>
<p>
	<span class="subheader">Enable VHOST using this web interface</span>
	<br>
	To enable a VHOST using the web interface do these steps:
	<ul>
		<li>If not already present: add or git clone a webroot folder with the name of the new server into the basic webroot folder (e.g. /var/www/<i>servername</i>)</li>
		<li>Refresh this page</i></li>
		<li>Click the 'Enable' button next to the server name you just added.</li>
		<li>Add an entry for <i>servername</i> into your local(!) /etc/hosts file and link it to the IP address of this server ( <?php echo file_get_contents('ip.txt');?>).</li>
	</ul>
</p>
<hr>
<p>
	<span class="subheader">New multihost command line commands</span>
	<br>
	During the setup of the multihost server several multihost CLI commands were installed that need to run as superuser (sudo <i>command</i>):
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
	<span class="subheader">Enable VHOST using the CLI</span>
	<br>
	To enable a VHOST using the CLI do these steps in a terminal:
	<ul>
		<li>If not already present: add or git clone a webroot folder with the name of the new server into the basic webroot folder (e.g. /var/www/<i>servername</i>)</li>
		<li>Issue 'sudo enable_vhost <i>servername</i>'</li>
		<li>Add an entry for <i>servername</i> into your local(!) /etc/hosts file and link it to the IP address of this server ( <?php echo file_get_contents('ip.txt');?>).</li>
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