<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    This controlls _everything_ in whube
     */

session_start();

if ( ! isset ( $_SESSION['id'] ) ) {
	$_SESSION['id'] = -1;
	/*
	 * This sets the id to -1 if the user
	 * is not logged in
	 */
}

$app_root        = dirname(  __FILE__ ) . "/";
$controller      = basename( __FILE__ );

include( $app_root . "conf/site.php" );
include( $app_root . "conf/vcs.php" );

include( $app_root . "libs/php/globals.php" );
include( $app_root . "libs/php/easter.php" );
include( $app_root . "libs/php/core.php" );

if ($handle = opendir( $app_root . "model/" )) { // open up the model directory
	while (false !== ($file = readdir($handle))) { // for each file
		if ( $file != "." && $file != ".." ) { // ignore . / ..
			$ftest = $file;                // "backup" file
			if (preg_match("/.*\.php$/i", $ftest)) {
				include( $app_root . "model/" . $file ); // include all .php files
			}
		}
	}
}

header( "Wisdom-Turd: " . getQuip() ); // wisdom turds!

if ( file_exists( $app_root . "install/install.php" ) ) {
	include( $app_root . "install/install.php" ); // Disable access
	include( $app_root . "view/view.php" );       // if we're not set up.
	exit(0); // stop executing the controller code
}

$p = htmlentities( $_GET['p'], ENT_QUOTES);
$toks = explode( "/", $p );

$argv = $toks;
$argc = sizeof( $toks ); // these get passed to the view

$argl = "";

for ( $i = 1; $i < $argc; ++$i ) {
	$argl .= $argv[$i] . "/";
}

if (
	isset ( $toks[0] ) &&
	$toks[0] != ""
) { // if there's a directive, use it.
	$idz = $app_root . "content/" . basename( $toks[0] ) . ".php";
	if ( file_exists( $idz ) ) {
		include( $idz );     // all good :)
	} else {
		include( $app_root . "content/default.php" ); // fake the err.
	}
} else {
	header("Location: $SITE_PREFIX"); // redirect to the app root
}

include( $app_root . "view/view.php" ); // include the head/foot

?>

