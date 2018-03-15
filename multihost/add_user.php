<?php
$username = $_GET['username'];
$new_password = md5($_GET['new_password']);
$file = "users";

//echo "that's it...";

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
};
fwrite($userfile, "$username, $new_password\n");
fclose($userfile);
echo json_encode(array('username'=>$username, 'password'=>$new_password));


