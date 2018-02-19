<span class="subheader">New multihost command line commands</span>
<br>
During the setup of the multihost server several multihost CLI commands were installed that need to run as superuser (sudo <i>command</i>):
<ul>
	<li><b>run_multihost</b> - (Re-)start the multihost server. By default it will run with Centos7 and PHP7.</li>
	<li><b>restart_multihost</b> - Restart the web server inside the Docker container to reload new config.<br>This will automatically be issued when enabling or disabling VHOSTs.</li>
	<li><b>enable_vhost <i>servername</i></b> - Enable a VHOST (see below).</li>
	<li><b>disable_vhost <i>servername</i></b> - Disable an existing VHOST and all it's settings - but <b>NOT</b> removing the web data.</li>
	<li><b>purge_moodlecache <i>servername</i></b> - Removing any cached moodledata for the given servername.</li>
</ul>
