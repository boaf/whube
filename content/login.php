<?php

if ( isset( $argv[1] ) ) {
	// that's a redirect!
	$TARGET_PAGE = clean( $argl );
} else {
	$TARGET_PAGE = "home";
}

if ( isset ( $_SESSION['id'] ) && $_SESSION['id'] > 0 ) {
	header( "Location: " . $SITE_PREFIX . "t/" . $TARGET_PAGE );
}

useScript( "md5.js" );
useScript( "login.js" );

$_SESSION['token'] = md5( time() );
$TOKEN = $_SESSION['token'];

$TITLE   = "Login Page";
$CONTENT = "
	<div class = 'shit' id = 'remove-me' >
		<div class = 'content' >
			<b>YOU DON'T HAVE JAVASCRIPT</b>.
			DON'T LOG IN WITHOUT JAVASCRIPT. YOUR PASSWORD WILL BE
			SENT IN PLAINTEXT. DON'T LOG IN WITHOUT JAVASCRIPT!!!!
		</div>
	</div>
	<script type = 'text/javascript' >
		$('#remove-me').hide();
	</script>
	<form action = '" . $SITE_PREFIX . "l/gate' method = 'post' >
	<p>
		<input type = 'hidden' name = 'token'  value = '" . $TOKEN . "' />
		<input type = 'hidden' name = 'bounce' value = '" . $TARGET_PAGE . "' />
		<input type = 'hidden' name = 'pass' />
	</p>
	<table>
		<tr>
			<td>Login Name</td>
			<td><input class = 'bigme name' type = 'text' name = 'name' /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input class = 'bigme password' type = 'password' name = 'pw' /></td>
		</tr>
		<tr>
			<td></td>
			<td><input name = 'login' type = 'submit' value = 'Let me in!' onclick = 'preSubmit(this.form)' /></td>
		</tr>
	</table>
	</form>
";

?>
