<?php
$version = '2.5';
$date = '2018-03-20';

session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

# A simple routing table
if(array_key_exists('purge_moodlecache',$_POST)) purge_moodlecache($_POST['vhost']);
# allow these routes for logged in users only
	if(isset($_SESSION['logged_in'])){
	if(array_key_exists('enable',$_POST)) enable_vhost($_POST['vhost']);
	if(array_key_exists('disable',$_POST)) disable_vhost($_POST['vhost']);
	if(array_key_exists('reload_apache',$_POST)) reload_apache();
	#if(array_key_exists('default',$_POST)) make_default($_POST['vhost']);
}
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta charset="utf-8" />
	<title>Docker MultiHost</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.js"></script>

	<link rel="stylesheet" href="lib/main.css">
</head>

<body>
	<div id="grey_mask" style="display: none;"></div>
<p id="demo"></p>
	<?php
		if(isset($_SESSION['logged_in'])) {
			echo "<div hidden id='logged_in'>".$_SESSION['logged_in']."</div>";
		}
	?>
	<div class="ui grid container">
		<div class="sixteen wide column">
		  	<p><p>
			<span class="header">Docker <span class="multi"><b>multi</b>host</span></span>
			<span><button class="ui right floated orange mini button login_button" id="loginbtn">Login</button></span>
			<span><button style="display: none;" class="ui right floated mini button edit_users_button" id="editusersbtn">Edit Users</button></span>
			<hr>	  	
		</div>
		<div id="intro_section" class="ten wide column"></div>
  		<div id="details_section" class="six wide column"></div>
		<div id="enabled_section" class="ten wide column"></div>
		<div id="disabled_section" class="admin six wide column" style="display: none;"></div>  
		<div id="manage_section" class="sixteen wide column" style="display: none;"></div>
		<div style="display: none;" class="eight wide column" id="cli_section"></div>
		<div style="display: none;" class="eight wide column" id="cli_enable_section"></div>
  		<div class="sixteen wide column">
			<hr>
			<?php
			echo "<span class='footer'>v.$version | $date</span>";
			?>
 		</div>
		<!--  test area  -->
		<div id="test_area" class="test_area" style="display: none;"></div>
	</div>

<!--  the login box  -->
	<div class="user_box" id="login_box" style="display: none;">
		<table class="ui table">
			<thead>
				<tr><td colspan='2'><h2>Login</h2><hr></td></tr>
			</thead>
			<tbody>
				<tr><td>Username: </td><td><input type="text" name="username"></td></tr>
				<tr><td>Password: </td><td><input type="password" name="password"></td></tr>
				<tr><td colspan='2'><hr></td></tr>
				<tr>
					<td><div class="ui mini button cancel_button" id="cancel_login_btn">Cancel</div></td>
					<td><div class="ui mini orange button" id="login_submit_btn">Login</div></td>
				</tr>
				<tr><td colspan="2"><div class="error_msg" id="login_msg"></div></td></tr>
			</tbody>			
		</table>
	</div>

<!--  the user list box  -->
	<div id="users_box" class="user_box" style="display: none;"></div>

<!--  the add user box  -->
	<div id="user_add_box" class="user_box" style="display: none;"></div>

<!--  the edit user box  -->
	<div id="user_edit_box" class="user_box" style="display: none;"></div>

</body>

<script type="text/javascript" src="lib/multihost.js"></script>
<script>

function myTimer() {
    var d = new Date();
    document.getElementById("demo").innerHTML = d.toLocaleTimeString();
    reload_vhosts();
//    console.log('now intervalling correctly...!');
}
</script>
