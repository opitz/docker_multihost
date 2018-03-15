<?php
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

	return '<form id = "enable_form_'.$vhost.'" method="post">
	<input type = "hidden" name = "vhost" value = "'.$vhost.'" />
	<input type="hidden" name="enable" id="enable_field_'.$vhost.'" value="Enable" />
	<button class="ui green mini button enable_button" id="enable_'.$vhost.'">Enable</button>
	</form>
';
}

#------------------------------------------------------------------------------
function disable_button($vhost = false) {
	if(!$vhost) return false;
	if($vhost == 'multihost') return 'mandatory';
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '';

	return '<form id = "disable_form_'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
  	<input type="hidden" name="disable" id="disable_field_'.$vhost.'" value="Disable" />
	<button style="display: none;" class="admin ui red mini button disable_button" id="disable_'.$vhost.'">Disable</button>
		</form>
';
}

#------------------------------------------------------------------------------
function cache_button($vhost = false) {
	if(!$vhost) return false;
	if(!file_exists('/var/moodledata/'.$vhost.'/sessions')) return "<button class='ui mini disabled button cached_button' id='$vhost'>Cache purged</button>";

	return '<form id = "cache_form_'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
  	<input type="hidden" name="purge_moodlecache" id="cache_field_'.$vhost.'" value="Purge Cache" />
	<button class="ui mini button cache_button" id="cache_'.$vhost.'">Purge Cache</button>
	</form>
';
}

#------------------------------------------------------------------------------
function reload_button() {
	return '<form method="post" id="reload">
  <input type="hidden" name="reload_apache" id="reload_field_'.$vhost.'" value="Reload Apache Configuration" />
	<button class="ui mini button reload_button" id="reload_'.$vhost.'">Reload</button>
	</form>
';
}

#------------------------------------------------------------------------------
function edit_button($id = false) {
	if(!$id) return false;

	return '<button class="ui mini button reload_button" id="userbtn_'.$id.'">Edit</button>';
}

#------------------------------------------------------------------------------
function default_button($vhost = false) {
	if(!$vhost) return false;
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '>> default <<';

	return '<form id = "default_form_'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="default" id="default_'.$vhost.'" value="Make default" />
</form>
';
}
#------------------------------------------------------------------------------
function server_button($vhost = false, $php_version = false, $port = false) {
	if(!$vhost) return false;
	if(!$php_version) return false;

	return '<button class="ui blue mini button server_button" id="server_'.$vhost.'" value="https://'.$_SERVER['HTTP_HOST'].':'.$port.'/'.$vhost.'">'.$vhost.'</button>';
}

