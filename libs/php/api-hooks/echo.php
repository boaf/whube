<?php
/*

$d['errors'] = true;
$d['success'] = false;
$d['message'] = "Unknown error";

*/

function handleEchoRequest( $argv ) {

	$ret['message'] = $argv[1];
	$ret['errors']  = false;
	$ret['success'] = true;
	return $ret;
}

$hooks['echo'] = "handleEchoRequest";

?>
