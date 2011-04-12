<?php
/**
 * SQL class file, All SQL model work lies herein
 * 
 * Super awesome SQL interface class
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "sql" ) ) {
class sql {

	var $link;
	var $result;

	function __construct( $connect = true ) {
		if ( $connect ) {
			$path = dirname(__FILE__) . "/";
			include( $path . "../conf/sql.php" );
			$this->connect( $mysql_host, $mysql_user, $mysql_pass, $mysql_data );
		}
	}
	function test() {
                $path = dirname(__FILE__) . "/";
                include( $path . "../conf/sql.php" );
                return $this->test_auth( $mysql_host, $mysql_user, $mysql_pass, $mysql_data );
	}
	function test_fail() {
		echo "\n\nConnection Failed! Bad auth! check conf/sql.php!\n\n";
		$this->fail = true;
	}
	function test_auth(  $host, $name, $pass, $db ) {
		$this->fail = false;
                $link = mysql_connect(
                        $host,
                        $name,
                        $pass
                ) or $this->test_fail();
		return ! $this->fail;
	}
	function __destruct() {
		$this->destruct();
	}
	function destruct() {
		// mysql_close( $this->link );
	}
	function connect( $host, $name, $pass, $db ) {
		$this->link = mysql_connect(
			$host,
			$name,
			$pass
		) or die( mysql_error() );
		mysql_select_db( $db ) or die( mysql_error() ); // Lets get our Database
	}
	function getNextRow() {
		if ( $this->result != NULL ) {
			if ( $row = mysql_fetch_array( $this->result ) ) {
				return $row;
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}
	function numrows() {
		return mysql_num_rows($this->result);
	}
	function getLastID() {
		return mysql_insert_id( $this->link );
	}
	function query( $query ) {
		$this->result = mysql_query($query) or die( mysql_error() );  // Preform the Query.
	}
}
}
?>
