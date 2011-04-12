<?php

include( "../conf/site.php" );
include( "../conf/sql.php"  );

include( "../model/sql.php" );

$counter_for_return = 10;

function testie( $var ) {
	if ( isset( $var ) && $var != "" ) {
		return true;
	} else {
		return false;
	}
}


function unit( $id, $var ) {
	global $counter_for_return;
	$counter_for_return++;
	echo "Running test $counter_for_return -- ";
	if ( testie( $var ) ) {
		echo "[32m$id[0m is all set.\n";
	} else {
		echo "[31m$id[0m is unset. This is a big deal. Please fix this\n";
		exit($counter_for_return);
	}
}

unit( "tld", $TLD );

$SQL = new sql( false );

if ( $SQL->test() ) {
	$SQL = "GO FOR THE GOLD";
} else {
	$SQL = "";
}

unit( "sql-conf", $SQL );

?>
