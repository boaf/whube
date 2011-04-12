<?php
    /*
     *  License:     AGPLv3
     *  Author:      Thomas Martin <tenach@whube.com>
     *  Description:
     *    This is where you POST a new registration
     */
session_start();

$app_root        = dirname(  __FILE__ ) . "/../../";

include( $app_root . "libs/php/globals.php" );
include( $app_root . "conf/site.php" );
include( $app_root . "model/user.php" );
include( $app_root . "model/events.php" );

if ( isset ( $_POST['firstname'] ) && $_POST['firstname'] !== '' ) { // hidden input for the win!
	header ( "Location: $SITE_PREFIX" . "t/welcome" ); // Dump the scum without telling them why >:3
}

if (
	isset ( $_POST['username'] ) &&
	isset ( $_POST['relaname'] ) && // Mispelled on purpose for bot swatting!
	isset ( $_POST['email']	) &&
	isset ( $_POST['pass1']	)
) {

	$r = $USER_OBJECT;
	$u = $USER_OBJECT;

	// let's first verify email and password

	$email	= htmlentities( $_POST['email'], ENT_QUOTES);
	
	$password = htmlentities( $_POST['pass1'], ENT_QUOTES);
	
	$vemail = $r->validate_email( $email );
	$vpass  = $r->validate_password( $password, 4, 32 ); 
	$password = md5( $password );
	
	if ( $vemail == FALSE ) {
		$_SESSION['err'] = "Your email address " . $email . " sucks ass, or is totally fake. Fix it.";
		header("Location: $SITE_PREFIX" . "t/register");
		exit(0);
	}

	if ( $vpass ) {
		$_SESSION['err'] = "Does your mother know you do that with your password?";
		header("Location: $SITE_PREFIX" . "t/register");
		exit(0);
	}
	
	$u->getByCol( "username", $argv[1] );
	$user = $u->getNext();
	$users = $r->getAllUsers();
	
	$i=0;
	$numUsers = count( $users );

	$vuser = FALSE;
	foreach( $users as $user ) {
		if( $_POST['username'] == $user['username'] ) {
			if( !isset( $_POST['update'] ) ) {
				$_SESSION['err'] = "Hey hey, someone already has that user name.";
				header("Location: $SITE_PREFIX" . "t/register");
			}
		} else {
			$vuser = TRUE;
		}
	}

	$USER_OBJECT->GetByCol('username', $_POST['username'] );
	$updUser = $USER_OBJECT->getNext();
	
	if( isset( $_POST['update'] ) && $_POST['pass1'] == '' ) {
		$password = $updUser['password'];
	}
	
	if( $vuser == TRUE ) {
		$locale = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		$fields = array(
			"real_name" => clean($_POST['relaname']),
			"username"  => clean($_POST['username']),
			"email"     => clean($_POST['email']),
			"locale"    => clean($locale[1]),
			"timezone"  => clean($_POST['timezone']),
			"password"  => $password, // too plain, not enough salt. :(
		);
		
		if( isset( $_POST['update'] ) ) {
			$newuser = $r->UpdateByPK( $updUser['uID'], $fields );
			
			$_SESSION['msg'] = "User updated";
			header( "Location: $SITE_PREFIX" . "t/admin" );
			exit(0);
		}
		
		$newuser = $r->createNew( $fields );

		if ( $BUILTIN_EMAIL_ENABLE ) {

$message =

"Hey there!

Welcome to Whube, " . $fields['real_name'] . "!

You're a fantastic person, and this email is to just
to assure you that the administrators over at " . $SITE_PREFIX . "
love you very much.

Thanks for wanting to help out, and welcome to the community!

" . $BUILTIN_EMAIL_SIG;
			sendEmail( $BUILTIN_EMAIL_ADDR, $fields['email'], "Welcome to Whube, " . $fields['username'] . "!", $message );
		}	
	}
		$_SESSION['msg'] = "There you go, now you just need to log in ;D";
		header("Location: $SITE_PREFIX" . "t/login");
		exit(0);
	} else {
		$_SESSION['err'] = "Please fill in all the forms!";
		header("Location: $SITE_PREFIX" . "t/register");
		exit(0);
	}


?>

