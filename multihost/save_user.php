<?php
$username = $_GET['username'];
$new_password = md5($_GET['new_password']);
$file = "users";

$userfile = fopen($file, "r+") or die("Unable to read user file!");

$users = '';

while(!feof($userfile)) {
	$userline = fgets($userfile);
 	$user = explode(',', $userline);
 	if(trim($user[0]) == $username){
 		$users .= "$username, $new_password\n";
 	} else {
 		$users .= $userline;
 	}
}
fclose($userfile);
$userfile = fopen($file, "w") or die("Unable to write user file!");
fwrite($userfile, $users);
fclose($userfile);
echo $users;


