<?php
#
#	This file contains all ajax vhost functions called by jQuery in multihost.js
#	using the argument 'action' a switch will determine what to do.
#

#------------------------------------------------------------------------------
function enable_vhost($vhost = false){
	shell_exec("../cli/enable_vhost.sh $vhost");
}

#------------------------------------------------------------------------------
function disable_vhost($vhost = false){
	shell_exec("../cli/disable_vhost.sh $vhost");
}

#------------------------------------------------------------------------------
function check_moodlecache($vhost = false){
	if(!$vhost) return false;
	return file_exists("var/moodlecache/$vhost");
}

#------------------------------------------------------------------------------
function purge_moodlecache($vhost = false){
	shell_exec("sudo ../cli/purge_moodlecache.sh $vhost");
	return "==> purged moodlecache for $vhost";
}

//============================================================================
$file = "/etc/multihost.user"; // path INSIDE the Docker container to the file where the user data is stored
$html = '';
if(!isset($_GET['action'])){
	echo $html;
}

if(!isset($_GET['username'])){
	echo $html;
}

$action = $_GET['action'];
$vhost = $_GET['vhost'];
if(!$vhost) {
	echo "<dev class='alert'>No vhost given - please investigate!</dev>";
	return false;
}

switch($action) {
	case "enable_vhost" :
		echo enable_vhost($vhost);
		break;
	case "disable_vhost" :
		echo disable_vhost($vhost);
		break;
	case "check_moodlecache" :
		echo check_moodlecache($vhost);
		break;
	case "purge_moodlecache" :
		echo purge_moodlecache($vhost);
		break;
	default:
		echo "ERROR: Action $action not recognised! (or no action given)";
}

