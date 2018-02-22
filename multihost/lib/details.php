<span>
	<span class="subheader">Server details</span>
	<p><p>
		This page is currently served by:<p>
	<?php
	echo "<ul>";
	echo "<li>Docker container: <b>".php_uname('n')."</b></li>";
	if(file_exists('created'.$_SERVER['SERVER_PORT'].'.txt')) {
		echo "(Image created: " . file_get_contents('created'.$_SERVER['SERVER_PORT'].'.txt') . ")</li>";
	} else {
		echo "</li>";
	}
	if(file_exists('ip.txt')) {
		echo "<li>IP address: <b>" . file_get_contents('ip.txt').': '.$_SERVER['SERVER_PORT'] . "</b></li>";
	} else {
		echo "<li><span class='alert'>No IP address found - this is weird...!</span></li>";
	}
	echo "<li>PHP version: <b>".phpversion()."</b></li>";
	if($xdebug=phpversion('xdebug')) echo"<li><b>xdebug $xdebug</b> is installed</li><p>";
	echo "</ul>";
	?>
</span>