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

	return '<form id = "'.$vhost.'" method="post">
	<input type = "hidden" name = "vhost" value = "'.$vhost.'" />
	<input type="hidden" name="enable" id="'.$vhost.'" value="Enable" />
	<button class="ui green mini button enable_button" id="'.$vhost.'">Enable</button>
	</form>
';
}

#------------------------------------------------------------------------------
function disable_button($vhost = false) {
	if(!$vhost) return false;
	if($vhost == 'multihost') return 'mandatory';
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '';

	return '<form id = "'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
  	<input type="hidden" name="disable" id="'.$vhost.'" value="Disable" />
	<button class="ui red mini button disable_button" id="'.$vhost.'">Disable</button>
		</form>
';
}

#------------------------------------------------------------------------------
function cache_button($vhost = false) {
	if(!$vhost) return false;
	if(!file_exists('/var/moodledata/'.$vhost.'/sessions')) return "<button class='ui mini disabled button cached_button' id='$vhost'>Cache purged</button>";

	return '<form id = "'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
  	<input type="hidden" name="purge_moodlecache" id="'.$vhost.'" value="Purge Cache" />
	<button class="ui mini button cache_button" id="'.$vhost.'">Purge Cache</button>
	</form>
';
}

#------------------------------------------------------------------------------
function reload_button() {
	return '<form method="post" id="reload">
  <input type="hidden" name="reload_apache" id="'.$vhost.'" value="Reload Apache Configuration" />
	<button class="ui mini button reload_button" id="'.$vhost.'">Reload</button>
	</form>
';
}

#------------------------------------------------------------------------------
function default_button($vhost = false) {
	if(!$vhost) return false;
	if(realpath('/var/www/html') == '/var/www/'.$vhost) return '>> default <<';

	return '<form id = "'.$vhost.'" method="post">
	<input type ="hidden" name = "vhost" value = "'.$vhost.'" />
    <input class="button" type="submit" name="default" id="'.$vhost.'" value="Make default" />
</form>
';
}
#------------------------------------------------------------------------------
function server_button($vhost = false, $php_version = false, $port = false) {
	if(!$vhost) return false;
	if(!$php_version) return false;

	return '<button class="ui blue mini button server_button" id="'.$vhost.'" value="https://'.$_SERVER['HTTP_HOST'].':'.$port.'/'.$vhost.'">'.$vhost.'</button>';
}

