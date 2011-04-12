<?php
/**
 * Database Object stuff, handles all database mapping.
 *
 * Base database interface class
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */
if ( ! class_exists ( "dbobj" ) ) {

	$model_root = dirname(  __FILE__ ) . "/";
 
	if ( ! class_exists( "sql" ) ) {
		// last ditch...
		include( $model_root . "sql.php" );
	}

	include( $model_root . "../conf/sql.php" );
	include( $model_root . "events.php" );

	class dbobj {
		var $sql;
		var $table;
		var $pk_field;

		function dbobj( $table, $pk_field ) {
			global $TABLE_PREFIX;
			$this->table     = $TABLE_PREFIX.$table;
			$this->pk_field  = $pk_field;
			$this->sql       = new sql();
		}

		function getAll() {
			$this->sql->query( "SELECT * FROM " . $this->table . " ORDER BY " . $this->pk_field . " DESC" );
		}

		function createNew( $items ) {
			// key => value
			$keys = "";
			$values = "";
			foreach ( $items as $key => $value ) {
				if ( ! is_numeric( $value ) ) {
					$value = "'$value'";
				} 
				if ( $keys != "" ) { $keys .= ", "; }
				$keys .= $key;

				if ( $values != "" ) { $values .= ", "; }
				$values .= $value;
			}
			$this->sql->query( "INSERT INTO " . $this->table . " ( " . $keys . " ) VALUES ( " . $values . " );" );
			$ID = $this->sql->getLastID();
			$this->sql->query( "UPDATE " . $this->table . " SET startstamp=" . time() . " WHERE " . $this->pk_field . "=" . $ID . " ;" );
			$this->updateRoutine( "new", $ID );
			return $ID;
		}

		function updateStamp( $ID ) {
			$this->sql->query( "UPDATE " . $this->table . " SET trampstamp=" . time() . " WHERE " . $this->pk_field . "=" . $ID . " ;" );
		}

		function updateEvent( $STATUS, $ID ) {
			$u = new events();
			$this->getAllByPK( $ID );
			$row = $this->getNext();
			$ret[$this->table] = $row;
			$ret['status']     = $STATUS;
			$u->broadcast( json_encode($ret) );
		}

		function updateRoutine( $action, $id ) {
			$this->updateStamp( $id );
			$this->updateEvent( $action, $id );
		}

		function updateByPK( $PK, $tables ) {
			$QUERY = "UPDATE " . $this->table . " SET ";
			$nipflipflip = false;
			foreach ( $tables as $key => $value ) {
				if ( ! is_numeric( $value ) ) {
					$value = "'$value'";
				}
				if ( $nipflipflip ) {
					$QUERY .= ", ";
				} else {
					$nipflipflip = true;
				}
				$QUERY .= $key . " = " . $value;
			}
			$QUERY .= " WHERE " . $this->pk_field . " = '" . $PK . "';";
			$this->sql->query( $QUERY );
			$this->updateRoutine( "update", $PK );
		}

		function specialSelect( $query ) {
			$this->sql->query( "SELECT * FROM " . $this->table . " WHERE " . $query . ";" );
			return $this->numrows();
		}

		function customSelect( $thisRow, $thisTable, $query ) {
			global $TABLE_PREFIX;
			$yum = $this->sql->query( "SELECT " . $thisRow . " FROM " . $TABLE_PREFIX.$thisTable . " WHERE " . $query . ";" );
			$yum = $this->toArray($yum);
			return $yum;
		}

		function getAllByPK( $pk ) {
			$this->sql->query( "SELECT * FROM " . $this->table . " WHERE " . $this->pk_field . " = '" . $pk . "'; " );
		}
		function getByCol( $cID, $id ) {
	                $this->sql->query( "SELECT * FROM " . $this->table . " WHERE " . $cID . " = '" . $id . "'; " );
		}
		function searchByKey( $cID, $id ) {
	                $this->sql->query( "SELECT * FROM " . $this->table . " WHERE " . $cID . " LIKE '%" . $id . "%'; " );
		}
		function numRows() {
			return $this->sql->numrows();
		}
		function getNext() {
			return $this->sql->getNextRow();
		}
		function toArray($me) {
			$ret = array();
			while ( $row = $this->getNext() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}
	}
}
?>
