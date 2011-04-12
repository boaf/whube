<?php
/**
 * Cache class file, to handle the caching of remote data
 * 
 * Base cache superclass to do the cache babymaking.
 * @author   Thomas Martin <tenach@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "cacheobj" ) ) {

	$model_root = dirname(  __FILE__ ) . "/";

	if ( ! class_exists( "sql" ) ) {
		// last ditch...
		include( $model_root . "sql.php" );
	}

	include( $model_root . "../conf/sql.php" );
	include( $model_root . "events.php" );

	class cacheobj {
		var $sql;
		var $table;
		var $pk_field;
	
		function cacheobj( $table, $c_field ) {
			global $TABLE_PREFIX;
			$this->table     = $TABLE_PREFIX.$table;
			$this->c_field  = $c_field;
			$this->sql       = new sql();
		}
	
		function checkAge( $ID, $minutes = '' ) {
			$minute = 60;
			$ttl = $minute * $minutes;
			$curTime = time();
			$this->getAllByID( $ID );
			$cachePull = $this->sql->getNextRow();
			$refTime = clean( $cachePull["timestamp"] );
			$content = clean( $cachePull["cached_contents"] );
			$timeDifference = $curTime - $refTime;
			$ret = $ttl - $timeDifference;
			return $ret;
		}

		function updateStamp( $ID ) {
			$this->sql->query( "UPDATE " . $this->table . " SET timestamp=" . time() . " WHERE " . $this->c_field . "='" . $ID . "' ;" );
		}

		function updateCached( $ID, $contents ) {
			$this->sql->query( "UPDATE " . $this->table . " SET cached_contents='" . clean ( $contents ) . "' WHERE " . $this->c_field . "='" . $ID . "' ;" );
		}
	
		function getAllByID( $ID ) {
			$this->sql->query( "SELECT * FROM " . $this->table . " WHERE " . $this->c_field . " = '" . $ID . "';" );
		}
	}
}
?>
