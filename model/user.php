<?php
/**
 * User class stuff, all the user class model stuff.
 * 
 * User class to do CRUD work.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */
if ( ! class_exists ( "user" ) ) {

	if ( ! class_exists( "dbobj" ) ) {
	        // last ditch...
	        $model_root = dirname(  __FILE__ ) . "/";
	        include( $model_root . "dbobj.php" );
	}
	
	if ( ! class_exists( "EmailAddressValidator" ) ) {
			$root = dirname( __FILE__ ) . "/../" ;
			include( $root . "libs/php/emailAddressValidator.php" ); // fugly
	}
	

	class user extends dbobj {
		function user() {
			dbobj::dbobj("users", "uID");
		}

		function getAllUsers() {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "users;" );
			$ret = array();
			while ( $row = $sql->getNextRow() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}

		function validate_email( $email ) {
			$isValid = false;
			$validator = new EmailAddressValidator();
	        if ( $validator->check_email_address( $email ) ) {
				$isValid = true;
	        }
			return $isValid;
		}

		function teamMembership( $id ) {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "project_members WHERE userID = " . $id . ";" );
			$ret = array();
			while ( $row = $sql->getNextRow() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}
	
		function validate_password( $password, $min, $max ) {
			$password = trim( $password );
			$bad = eregi_replace( '( [a-zA-Z0-9_]{' . $min_char . ',' . $max_char . '} )', '', $password );
		
			if( empty( $bad ) ) {
				$isValid = TRUE;
			} else {
				$isValid = FALSE;
			}
			return $isValid;
		}
	}
}

$USER_OBJECT = new user();

?>
