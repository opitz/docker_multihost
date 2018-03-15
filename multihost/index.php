<?php
$version = '2.2';
$date = '2018-03-13';

include('lib/functions.php');

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
	<?php
//		if($_SESSION['logged_in'] != ''){
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
	  <div class="ten wide column">
	  	<?php include('lib/intro.php'); ?>
	  </div>
	  <div class="six wide column">
	  	<?php include('lib/details.php'); ?>
	  </div>
	  <div class="ten wide column">
	  	<?php include('lib/enabled.php'); ?>
	  </div>
	  <div style="display: none;" class="admin six wide column" id="disabled_section">
	  	<?php include('lib/disabled.php'); ?>
	  </div>
	  <div style="display: none;" class="sixteen wide column" id="manage_section">
	  	<hr>
	  	<?php include('lib/manage.php'); ?>
	  </div>
	  <div style="display: none;" class="eight wide column" id="cli_section">
	  	<hr>
	  	<?php include('lib/cli.php'); ?>
	  </div>
	  <div style="display: none;" class="eight wide column" id="cli_enable_section">
	  	<hr>
	  	<?php include('lib/cli_enable.php'); ?>
	  </div>
	  <div class="sixteen wide column">
		<hr>
		<?php
		echo "<span class='footer'>v.$version | $date</span>";
		?>
	  </div>
	</div>

<!-- ---------------- the login box ---------------- -->
	<div id="login_box" style="display: none;">
		<table class="ui table">
			<tbody>
				<tr><td>Username: </td><td><input type="text" name="username"></td></tr>
				<tr><td>Password: </td><td><input type="password" name="password"></td></tr>
				<tr><td><div class="ui mini button" id="login_cancel_btn">Cancel</div></td><td><div class="ui mini orange button" id="login_submit_btn">Login</div></td></tr>
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

<script type="text/javascript" src="multihost.js"></script>
<script>

//==========================================================================
$(document).ready(function(){

	$('.button0').click(function(){
		alert('clicked...' + $(this).attr('id'));
	});


//--------------------------------------------------------------------------
	$(document).on('click',".edit_user_btn", function(){
		user_id = $(this).attr('id');
		username = $('#user_'+user_id).html();
		$.ajax({
			url: 'edit_user_form.php',
			data: {username: username},
			type: 'get',
			success: function(result) {
				$('#user_edit_box').html('').append(result);
				$('#user_edit_box').show();
				$('#users_box').hide();
			},
			error: function(error){
				console.log('Error:');
				console.log(error);
				$('#login_msg').html('Login failed!');
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click',"#add_user_btn",function(){
		$.ajax({
			url: 'add_user_form.php',
			type: 'get',
			success: function(result) {
				console.log(result);
				$('#user_add_box').html('').append(result);
				$('#user_add_box').show();
				$('#users_box').hide();
			},
			error: function(error){
				console.log('Error:');
				console.log(error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click',".delete_user_btn",function(){
		user_id = ($(this).attr('id')-1000);
		username = $('#user_'+user_id).html();
		if(username === 'admin')
			alert("User 'admin' cannot be deleted!");
		else	
			if(confirm('Do you really want to delete user ' + username + '?')) {
				$.ajax({
					url: 'delete_user.php',
					data: {username: username},
					type: 'get',
					success: function(result) {
				$('#users_box').hide();
						console.log(result);
						console.log('User ' + username + ' has been deleted');
					},
					error: function(error){
						console.log('Error:');
						console.log(error);
					}
				});
			}
	});

//--------------------------------------------------------------------------
	$(document).on('click','#cancel_add_user_btn', function(){
		$('#user_add_msg').html('');
		$('#user_add_box').hide();
	});

//--------------------------------------------------------------------------
	$(document).on('click','#save_add_user_btn', function(){
		new_username = $('input[name="new_username"]').val();
		new_password = $('input[name="new_password"]').val();
		confirm_password = $('input[name="confirm_password"]').val();
		$.ajax({
			url: 'validate_user.php',
			data: {action: 'new_user', username: new_username, password: new_password, confirm_password: confirm_password},
			type: 'get',
			success: function(result) {
				if(result === 'ok') {
					$.ajax({
						url: 'add_user.php',
						data: {username: new_username, new_password: new_password},
						type: 'get',
						success: function(result) {
							console.log(result);
						},
						error: function(error){
							console.log('Error:');
							console.log(error);
						}
					});
					$('#user_add_msg').html('');
					$('#user_add_box').hide();
				} else {
					console.log('==> ' + result);
					$('#user_add_msg').html(result);
				}

			},
			error: function(error){
				console.log('Error:');
				console.log(error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click','#save_edit_user_btn', function(){
		username = $('#username').html();
		new_password = $('input[name="new_password"]').val();
		confirm_password = $('input[name="confirm_password"]').val();

		$.ajax({
			url: 'validate_user.php',
			data: {action: 'password', username: username, new_password: new_password, confirm_password: confirm_password},
			type: 'get',
			success: function(result) {
				if(result === 'ok') {
					$.ajax({
						url: 'save_user.php',
						data: {username: username, new_password: new_password},
						type: 'get',
						success: function(result) {
							console.log(result);
						},
						error: function(error){
							console.log('Error:');
							console.log(error);
						}
					});
					$('#user_edit_msg').html('');
					$('#user_edit_box').hide();
				} else {
					console.log('==> ' + result);
					$('#user_edit_msg').html(result);
				}

			},
			error: function(error){
				console.log('Error:');
				console.log(error);
			}
		});
	});


//--------------------------------------------------------------------------
	$(document).on('click','#cancel_edit_user_btn', function(){
		$('#user_edit_msg').html('');
		$('#user_edit_box').hide();
	});

});
	
</script>