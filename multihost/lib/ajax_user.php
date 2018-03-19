<?php
#
#	This file contains all ajax user functions called by jQuery in multihost.js
#	using the argument 'action' a switch will determine what to do.
#

//----------------------------------------------------------------------------
function login($file, $username, $password){
	session_start();
	$md5password = md5($password);
	$html='';
	$userfile = fopen($file, "r") or die("Unable to open user file!");
	// Output one line until end-of-file
	while(!feof($userfile)) {
	 	$user = explode(',',fgets($userfile));
		if($username == trim($user[0]) && md5($password) == trim($user[1])) {
			$_SESSION['logged_in'] = $username;
			fclose($userfile);
			return $username;
			break;			
	  	}
	}
	fclose($userfile);
	return $html;
}

//----------------------------------------------------------------------------
function logout(){
	session_start();
	session_unset();
	session_destroy();
	return 'ok';
}

//----------------------------------------------------------------------------
function get_user_list($file){
	$userfile = fopen($file, "r") or die("Unable to open user file $file !");
	$i=0;
	$html = "<table class='ui table'>";
	$html .= "<thead>";
	$html .= "<tr><td colspan='3'><h2>User List</h2><hr></td></tr>";
	$html .= "</thead>";
	$html .= "<tbody>";
	// Output one line until end-of-file
	while(!feof($userfile)) {
	 	$user = explode(',',fgets($userfile));
	 	if(strlen(trim($user[0])) > 0) {
		 	$html .= "<tr>
		 				<td><span id='user_$i' type='text' value='$user[0]'>$user[0]</span></td>
		 				<td hidden><input id='pass_$i' type='text' value='$user[1]'></td>
		 				<td><button class='ui mini button edit_user_btn' id='$i'>Edit User</button></td>
		 				<td><button class='ui mini button delete_user_btn' id='".($i+1000)."'>Delete User</button></td>
		 			</tr>";
		 	$i++;
	 	}
	}
	$html .= "<tr><td colspan='3'><hr></td></tr>";
	$html .= "<tr>
				<td></td>
				<td><div class='ui mini button cancel_button' id='cancel_user_btn'>Cancel</div></td>
				<td><div class='ui mini green button' id='add_user_btn'>Add User</div></td>
			</tr>";
	$html .= "<tr><td colspan='3'><div id='users_msg'></div></td></tr>";
	$html .= "</tbody>";
	$html .= "</table>";
	fclose($userfile);
	return $html;
}

//----------------------------------------------------------------------------
function add_user_form(){
	$html='';
	$html = "<table class='ui table'>";
	$html .= "<thead>";
	$html .= "<tr><td colspan='2'><h2>Add new User</h2><hr></td></tr>";
	$html .= "</thead>";
	$html .= "<tbody>";
	$html .= "<tr><td>New Username:</td><td><input type='text' name='new_username'></td></tr>";
	$html .= "<tr><td>New Password:</td><td><input type='password' name='new_password'></td></tr>";
	$html .= "<tr><td>Confirm New Password:</td><td><input type='password' name='confirm_password'></td></tr>";
	$html .= "<tr><td colspan='2'><hr></td></tr>";
	$html .= "<tr>
				<td><div class='ui mini button cancel_button' id='cancel_add_user_btn'>Cancel</div></td>
				<td><div class='ui mini green button' id='save_add_user_btn'>Add User</div></td>
			</tr>";
	$html .= "<tr><td colspan='2'><div class='error_msg' id='user_add_msg'></div></td></tr>";
	$html .= "</tbody>";
	$html .= "</table>";
	return $html;
}

//----------------------------------------------------------------------------
function edit_user_form($file, $username){
	$html='';
	$userfile = fopen($file, "r") or die("Unable to open user file!");
	// loop through all users until you find a match
	while(!feof($userfile)) {
	 	$user = explode(',',fgets($userfile));
	 	if (trim($user[0]) == $username) {
			$html .= "<form id='edit_user_form' method='post'>";
			$html .= "<table class='ui table'>";
			$html .= "<thead>";
			$html .= "<tr><td colspan='2'><h2>Edit User</h2><hr></td></tr>";
			$html .= "</thead>";
			$html .= "<tbody>";
	 		$html .= "<tr><td>Username:</td><td id='username'>$username</td></tr>";
	 		$html .= "<tr><td>New Password:</td><td><input type='password' name='new_password'></td></tr>";
	 		$html .= "<tr><td>Confirm New Password:</td><td><input type='password' name='confirm_password'></td></tr>";
			$html .= "<tr><td colspan='2'><hr></td></tr>";
	 		$html .= "<tr>
	 					<td><div class='ui mini button cancel_button' id='cancel_edit_user_btn'>Cancel</div></td>
	 					<td><div class='ui mini green button' id='save_edit_user_btn'>Save User</div></td>
	 				</tr>";
			$html .= "<tr><td colspan='2'><div class='error_msg' id='user_edit_msg'></div></td></tr>";
			$html .= "</tbody>";
			$html .= "</table>";
			$html .= "</form>";

	 		break 1;
	 	}
	}
	fclose($userfile);
	return $html;
}

//----------------------------------------------------------------------------
function add_user($file, $username, $password){
	$html='';
	$md5password = md5($password);
	$userfile = fopen($file, "a") or die("Unable to read user file!");
	fwrite($userfile, "$username, $md5password\n");
	fclose($userfile);
	return $username;
}

//----------------------------------------------------------------------------
function save_user($file, $username, $password){
	$html='';
	$userfile = fopen($file, "r+") or die("Unable to read user file!");
	$md5password = md5($password);
	$users = '';
	while(!feof($userfile)) {
		$userline = fgets($userfile);
	 	$user = explode(',', $userline);
	 	if(trim($user[0]) == $username){
	 		$users .= "$username, $md5password\n";
	 	} else {
	 		$users .= $userline;
	 	}
	}
	fclose($userfile);
	$userfile = fopen($file, "w") or die("Unable to write user file!");
	fwrite($userfile, $users);
	fclose($userfile);
	return $users;
}

//----------------------------------------------------------------------------
function delete_user($file, $username){
	$html='';
	$userfile = fopen($file, "r+") or die("Unable to read user file!");

	$users = '';

	while(!feof($userfile)) {
		$userline = fgets($userfile);
	 	$user = explode(',', $userline);
	 	if(trim($user[0]) != $username){
	 		$users .= $userline;
	 	}
	}
	fclose($userfile);
	$userfile = fopen($file, "w") or die("Unable to write user file!");
	fwrite($userfile, $users);
	fclose($userfile);
	return $users;
}

//----------------------------------------------------------------------------
function validate_user($file, $username, $password, $confirm_password){
	$min_length = 5;
	$result = 'ok';
	if(strlen($username) == 0) return "ERROR: Username cannot be empty!";
	$userfile = fopen($file, "r") or die("Unable to read user file!");
	while(!feof($userfile)) {
		$userline = fgets($userfile);
	 	$user = explode(',', $userline);
	 	if(trim($user[0]) == $username){
	 		$result = "ERROR: Username $username already taken!";
	 		break;
	 	}
	}
	fclose($userfile);
	if($password != $confirm_password) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Passwords do not match!";
	if(strlen($password)<$min_length) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Password must be at least $min_length characters long!";
	return $result;
}

//----------------------------------------------------------------------------
function validate_password($password, $confirm_password){
	$min_length = 5;
	$result = 'ok';
		if($password != $confirm_password) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Passwords do not match!";
		if(strlen($password)<$min_length) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Password must be at least $min_length characters long!";
	return $result;
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
$username = $_GET['username'];
$password = $_GET['password'];
$confirm_password = $_GET['confirm_password'];


switch($action) {
	case "login" :
		echo login($file, $username, $password);
		break;
	case "add_user_form" :
		echo add_user_form();
		break;
	case "add_user" :
		echo add_user($file, $username, $password);
		break;
	case "edit_user_form" :
		echo edit_user_form($file, $username);
		break;
	case "save_user" :
		echo save_user($file, $username, $password);
		break;
	case "delete_user" :
		echo delete_user($file, $username);
		break;
	case "get_user_list" :
		echo get_user_list($file);
		break;
	case "get_user_data" :
		echo get_user_data($file, $username, $password);
		break;
	case "validate_user" :
		echo validate_user($file, $username, $password, $confirm_password);
		break;
	case "validate_password" :
		echo validate_password($password, $confirm_password);
		break;
	default:
		echo logout();
}

