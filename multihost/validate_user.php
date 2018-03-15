<?php
$action = $_GET['action'];
$username = $_GET['username'];
$password = $_GET['password'];
$confirm_password = $_GET['confirm_password'];

$min_length = 5;

$result = 'ok';
switch ($action){
	case 'new_user':
		if(strlen($username) == 0) {
			$result = "ERROR: Username cannot be empty!";
			break;
		}
		$file = "users";
		$userfile = fopen($file, "r") or die("Unable to read user file!");
		while(!feof($userfile)) {
			$userline = fgets($userfile);
		 	$user = explode(',', $userline);
		 	if(trim($user[0]) == $username){
		 		$result = "ERROR: Uhername $username already taken!";
		 		break;
		 	}
		}
		fclose($userfile);

		if($password != $confirm_password) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Passwords do not match!";
		if(strlen($password)<$min_length) $result = ($result == 'ok' ? '' : $result.'<br>')."ERROR: Password must be at least $min_length characters long!";

		break;
	case 'password':
		if($new_password != $confirm_password) $result = "ERROR: Passwords do not match!";
		if(strlen($new_password)<$min_length) $result = "ERROR: Password must be at least $min_length characters long!";
		break;
	default:
		$result = "ERROR: No action specified!";
}
echo $result;


