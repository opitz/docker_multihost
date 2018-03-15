<?php

$html = "<table class='ui table'>";
$html .= "<thead>";
$html .= '<tr><td><h2>Add new User</h2></td></tr>';
$html .= "</thead>";
$html .= "<tbody>";


$html .= "<tr><td>New Username:</td><td><input type='text' name='new_username'></td></tr>";
$html .= "<tr><td>New Password:</td><td><input type='text' name='new_password'></td></tr>";
$html .= "<tr><td>Confirm New Password:</td><td><input type='text' name='confirm_password'></td></tr>";

$html .= "<tr><td colspan='3'><hr></td></tr>";
$html .= "<tr>
			<td></td>
			<td><div class='ui mini button' id='cancel_add_user_btn'>Cancel</div></td>
			<td><div class='ui mini green button' id='save_add_user_btn'>Add User</div></td>
		</tr>";
$html .= "<tr><td colspan='3'><div class='error_msg' id='user_add_msg'></div></td></tr>";
$html .= "</tbody>";
$html .= "</table>";

echo $html;


