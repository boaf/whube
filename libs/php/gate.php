<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    Login gate. THOU SHAL NOT PASS
     */

session_start();

$app_root = dirname(  __FILE__ ) . "/../../";

include( $app_root . "model/user.php" );
include( $app_root . "conf/site.php" );
include( $app_root . "libs/php/globals.php" );

if ( isset( $_POST['logout'] ) ) {
	session_destroy();
	session_start();
	$_SESSION['msg'] = "See ya' later! I miss ya already!";
	header("Location: " . $SITE_PREFIX . "t/login");
	exit(0);
}

if ( isset( $_POST['login'] ) ) {
	if ( 
		isset( $_POST['name'] ) && $_POST['name'] != "" &&
		isset( $_POST['pass'] ) && $_POST['pass'] != ""
	) {

		if ( isset( $_POST['bounce'] ) && $_POST['bounce'] != "" ) {
			$TARGET_PAGE = clean( $_POST['bounce'] );
		} else {
			$TARGET_PAGE = "home";
		}

		$_SESSION['key'] = $_SESSION['token'];
		unset( $_SESSION['token'] );

		$user = new user();
		$user->getByCol( "username", $_POST['name'] );
		$foo = $user->getNext();

		$p_check = md5( $_SESSION['key'] . $foo['password'] );

		if ( $_POST['pass'] == $p_check ) {
			$_SESSION['rights'] = getRights( $foo['uID'] );
			
			if ( $_SESSION['rights']['banned'] ) {
				$_SESSION['msg'] = "You're banned, asshole. GTFO";
				header("Location: " . $SITE_PREFIX . "t/banned");
				exit(0);
			} else {


				$_SESSION['id']         =   $foo['uID'];
				$_SESSION['real_name']  =   $foo['real_name'];
				$_SESSION['username']   =   $foo['username'];
				$_SESSION['email']      =   $foo['email'];

// set patrick_stewart var for private / public stuff
//    $_SESSION['patrick_stewart'] = TRUE;
// Context / copied from:
//
//  http://www.youtube.com/watch?v=Fg_cwI1Xj4M ( Nawt a rickroll )
//     ^ this is lulzy. Watch.
//

				$_SESSION['msg'] = "Well done! Welcome in!";
				header("Location: " . $SITE_PREFIX . "t/" . $TARGET_PAGE);
				exit(0);
			}
		} else {
			$_SESSION['err'] = "Login Failure. Check username and password.";
			header("Location: " . $SITE_PREFIX . "t/login/" . $TARGET_PAGE);
			exit(0);
		}
	} else {
		$_SESSION['err'] = "Failed to submit the form completely";
		header("Location: $SITE_PREFIX" . "t/login/" . $TARGET_PAGE );
		exit(0);
	}
}

?>

