<!DOCTYPE html>
<html lang="en-GB">
<head>
<style>
.header { font-size:28px; }
.subheader { font-size:18px; font-weight: bold; }
.footer { font-size:12px; font-style: italic; }
</style>
	<title>Docker multihost</title>
</head>

<body>
<span class="header">Docker <b>multi</b>host</span>
<hr>
<p>
	This is the default homepage of Docker multihost, a web server based on Docker.<br>
	It is targeted at serving multiple virtual hosts (VHOSTs) with a single web server instance.
</p>
<?php 
if(file_exists('ip.txt')) { 
	echo "<p>
		<i>The server is currently running on IP address <b>" . file_get_contents('ip.txt') . "</b></i>
	</p>";
}
	?>
<p>
	This is the internal 'multihost-help' webpage which currently serves as the default web content.<br>
	Please see below how to set up your own VHOSTs and how to make one of then the default content served.
</p>
<p>
	<b>Please note:</b><br>
	If you want to access this help page after you made another VHOST the default one please add 'multihost-help'<br>
	to your local /etc/hosts/ file and connect it to the IP address of the machine this server is running on.
</p>
<p>
	<p>
		<span class="subheader">New multihost command line commands</span>
	</p>
	During the setup of the multihost server you installed several multihost CLI commands that need to run as superuser:
	<ul>
		<li><b>run_multihost</b> - (Re-)start the multihost server. By default it will run with Centos7 and PHP7.</li> 
		<li><b>restart_multihost</b> - Restart the web server inside the Docker container to reload new config.<br>This will automatically be issued when deploying or removing VHOSTs.</li> 
		<li><b>deploy_vhost <i>servername</i></b> - Deploy a new VHOST (see below).</li> 
		<li><b>remove_vhost <i>servername</i></b> - Remove an existing VHOST and all it's settings - but <b>NOT</b>> the web data.</li> 
		<li><b>multihost_default <i>servername</i></b> - Promoting one of the existing(!) VHOSTs to default web server.</li> 
		<li><b>purge_moodlecache <i>servername</i></b> - Removing any cached moodledata for the given servername.</li> 
	</ul>
</p>
<p>
	<p>
		<span class="subheader">Deploy new VHOST</span>
	</p>
	To deploy a new VHOST do these steps:
	<ul>
		<li>Add a webroot folder with the name of the new server into your basic webroot folder (e.g. /var/www/<i>servername</i>)</li>
		<li>Issue 'sudo deploy_vhost <i>servername</i>'</li>
		<li>Add an entry for <i>servername</i> into your local(!) /etc/hosts and link it to the IP address of this server (<?php echo file_get_contents('ip.txt');?>).</li>
	</ul>
	<br>
	<i>In the above cases replace <i>servername</i> with the actual server name you are about to deploy.</i>
</p>
<p>
	<p>
		<span class="subheader">Make a VHOST the default web content</span>
	</p>
	To make one of the existing VHOSTs the default one simply issue
	<ul>
		<li>sudo multihost_default <i>servername</i></li>
	</ul>

</p>

<hr>
<span class="footer">v.1.2 2017-11-27</span>
</body>