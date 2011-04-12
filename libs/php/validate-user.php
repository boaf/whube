<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    AJAX callbacks for checking username
     */

session_start();
// requireLogin();

$app_root        = dirname(  __FILE__ ) . "/../../";

include( $app_root . "conf/site.php" );
include( $app_root . "libs/php/globals.php" );
include( $app_root . "model/user.php" );

$s = new sql();

$d['errors'] = true;
$d['success'] = false;
$d['message'] = "Unknown error";

if ( isset ( $_GET['p'] ) ) {

	$id = clean( $_GET['p'] );

	if ( $_GET['p'] != "" ) {
		$p = new user();
		$p->searchByKey( "username", $id );
		$d['message'] = "Query executed with success " . $id . ".";
		$d['numrows'] = $p->numRows();
		if ( $p->numRows() < 1 ) {
			$d['message']   = "No project matches " . $id . ".";
			$d['bestmatch'] = "";
			$d['success'] = false;
		} else {
			$row = $p->getNext();
			$d['message'] = "We have a result for " . $id . ".";
			$d['bestmatch'] = $row['username'];
			if ( $row['username'] == $id ) {
				$d['success'] = true;
				$d['descr'] = $row['real_name'];
			}
		}
	} else {
		$d['errors']  = true;
		$d['success'] = false;
		$d['message'] = "";
	}
} else {
	$d['errors']  = true;
	$d['success'] = false;
	$d['message'] = "I don't know what user to lookup!";
}
echo json_encode( $d );
?>
