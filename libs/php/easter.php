<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    Spicy quips
     */

	$QUIP_LIST = array();
	function addQuip( $qu ) {
		global $QUIP_LIST;
		array_push( $QUIP_LIST, $qu );
	}
	include( dirname( __FILE__ ) . "/cookies.php" );
	function getQuip() {
		global $QUIP_LIST;
		$id = rand(0,sizeof($QUIP_LIST) - 1);
		return $QUIP_LIST[$id];
	}
?>
