<?php
useScript('timezone.js');
if( isset( $_SESSION ) && $_SESSION['id'] != -1 && isset( $_SESSION['rights'] ) && $_SESSION['rights']['admin'] != 1 ) {
	$_SESSION['err'] = "You are logged in!";
	header( "Location: $SITE_PREFIX" . "t/welcome" );
	exit(0);
}

$TITLE = "Register!";
$CONTENT  = "<h1>So, you want an account, eh?</h1>";

// Avoids notices.
$userID 	= '';
$realName = '';
$username = '';
$theEmail = '';
$locale 	= '';
$timezone = '';
$private 	= '';

if( isset( $_SESSION['rights'] ) && $_SESSION['rights']['admin'] == 1 ) {
	$TITLE = "Register a new user!";
	$CONTENT = "<h1>Register a new user</h1>";
	
	if( isset( $argv[2] ) && $argv[1] == "update" ) {
		$TITLE = "Updating " . $argv[2];
		$CONTENT = "<h1>" . $TITLE . "</h1>";
		$update = "<input type = 'hidden' name = 'update' id = 'update' value = '1' />";
		$notice = " <br />Only put password in if you want to change it.";
		
		$USER_OBJECT->getByCol( "username", $argv[2] );
		$tmpUser = $USER_OBJECT->getNext();
		
		//$userID 	= $tmpUser['uID']; // We shouldn't change this.
		$realName = $tmpUser['real_name'];
		$username = $tmpUser['username'];
		$theEmail = $tmpUser['email'];
		$locale 	= $tmpUser['locale'];
		$timezone = $tmpUser['timezone'];
		$private 	= $tmpUser['private'];
	}
	
}

$CONTENT .= "
<form action = '" . $SITE_PREFIX . "l/submit-register' method = 'post' >
<table>
	<tr>
		<td>Desired Username</td>
		<td></td>
		<td><input type = 'text' name = 'username' id = 'username' value = '" . $username . "' /></td>
	</tr>
	<tr>
		<td>Your Real Name ( First and Last, please )</td>
		<td></td>
		<td><input type = 'text' name = 'relaname' id = 'relaname' value = '" . $realName . "' /></td>
	</tr>
	<tr>
		<td>Email addy</td>
		<td></td>
		<td><input type = 'text' name = 'email' id = 'email' value = '" . $theEmail . "' /></td>
	</tr>
	<tr>
		<td>Password ( take one )" . $notice . "</td>
		<td></td>
		<td><input type = 'password' name = 'pass0' id = 'pass0' /></td>
	</tr>
	<tr>
		<td>Password ( take two )</td>
		<td></td>
		<td><input type = 'password' name = 'pass1' id = 'pass1' /></td>
	</tr>
	<tr>
		<td><input type = 'hidden' name = 'tz' id = 'tz' />";
		if( isset( $update ) ) $CONTENT .= $update;
		$CONTENT .= "</td><td></td>
		<td><input type = 'submit' name = 'new-user' id = 'submit' value = 'will you remember me?' /></td>
		<td><input type = 'text' name = 'firstname' id = 'firstname' value = '' /></td>
	</tr>
</table>
</form>
";

?>
