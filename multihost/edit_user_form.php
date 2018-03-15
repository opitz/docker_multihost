<?php
$html = '';
if(!isset($_GET['username'])){
	echo $html;
}

$username = $_GET['username'];
$file = "users";

$userfile = fopen($file, "r") or die("Unable to open user file!");

// loop through all users until you find a match
while(!feof($userfile)) {
 	$user = explode(',',fgets($userfile));
 	if (trim($user[0]) == $username) {
		$html .= "<form id='edit_user_form' method='post'>";
		$html .= "<table class='ui table'>";
		$html .= "<thead>";
		$html .= '<tr><td><h2>Edit User</h2></td></tr>';
		$html .= "</thead>";
		$html .= "<tbody>";
 		$html .= "<tr><td>Username:</td><td id='username'>$username</td></tr>";
 		$html .= "<tr><td>New Password:</td><td><input type='text' name='new_password'></td></tr>";
 		$html .= "<tr><td>Confirm New Password:</td><td><input type='text' name='confirm_password'></td></tr>";
 		$html .= "<tr><td><div class='ui mini button' id='cancel_edit_user_btn'>Cancel</div></td><td><div class='ui mini green button' id='save_edit_user_btn'>Save User</div></td></tr>";
		$html .= "<tr><td colspan='2'><div class='error_msg' id='user_edit_msg'></div></td></tr>";
		$html .= "</tbody>";
		$html .= "</table>";
		$html .= "</form>";

 		break 1;
 	}
}

fclose($userfile);
echo $html;
