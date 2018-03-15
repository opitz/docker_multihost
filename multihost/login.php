<?php
//	session_set_cookie_params(1800,"/"); // session will expire after 1800 seconds (=0.5 h)
	session_start();
	$username = $_POST['username'];
	$password = $_POST['password'];
	$file = "users";

	$userfile = fopen($file, "r") or die("Unable to open user file!");

	// Output one line until end-of-file
	while(!feof($userfile)) {
	 	$user = explode(',',fgets($userfile));
		if($username == trim($user[0]) && md5($password) == trim($user[1])) {
			$_SESSION['logged_in'] = $username;
			fclose($userfile);
			echo $username;
			break;			
	  	}
	}
	fclose($userfile);
	echo '';

