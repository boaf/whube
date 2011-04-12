<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    AJAX callbacks for getting internal data
     */

session_start();

$app_root = dirname(  __FILE__ ) . "/../../";

include( $app_root . "conf/site.php" );
include( $app_root . "libs/php/globals.php" );
include( $app_root . "libs/php/core.php" );
include( $app_root . "model/user.php" );
include( $app_root . "model/project.php" );
include( $app_root . "model/bug.php" );

// requireLocalIP();// external

$hooks = array();

if ($handle = opendir( dirname(__FILE__) . "/" . "api-hooks/" )) {
	while (false !== ($file = readdir($handle))) {
		// The "i" after the pattern delimiter indicates a case-insensitive search
		if ( $file != "." && $file != ".." ) {
			$ftest = $file;
			if (preg_match("/.*\.php$/i", $ftest)) {
				include( dirname(__FILE__) . "/" . "api-hooks/" . $file );
			}
		}
	}
}

$s = new sql();

$d['errors'] = true;
$d['success'] = false;
$d['message'] = "Unknown error";

$p = htmlentities( $_GET['p'], ENT_QUOTES);
$toks = explode( "/", $p );

$argv = $toks;
$argc = sizeof( $toks );

if ( isset ( $argv[0] ) && $argv[0] != "" ) {
	if ( isset ( $hooks[$argv[0]] ) ) {
		$d = $hooks[$argv[0]]($argv);
	}
}


$d['apiv']             = $API_VERSION;
$d['api_major_number'] = $API_COMPATV;

echo json_encode( $d );

?>
