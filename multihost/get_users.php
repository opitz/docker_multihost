<?php
$file = "users";

$userfile = fopen($file, "r") or die("Unable to open user file!");

$i=0;
$html = "<table class='ui table'>";
$html .= "<thead>";
$html .= '<tr><td><h2>User List</h2></td></tr>';
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
			<td><div class='ui mini button' id='cancel_user_btn'>Cancel</div></td>
			<td><div class='ui mini green button' id='add_user_btn'>Add User</div></td>
		</tr>";
$html .= "<tr><td colspan='3'><div id='users_msg'></div></td></tr>";
$html .= "</tbody>";
$html .= "</table>";
fclose($userfile);
echo $html;


