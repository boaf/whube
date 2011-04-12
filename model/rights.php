<?php
/**
 * rights class file, All rights model work lies herein
 * 
 * Base rights class to do bug CRUD work.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "rights" ) ) {

	if ( ! class_exists( "dbobj" ) ) {
	        // last ditch...
	        $model_root = dirname(  __FILE__ ) . "/";
	        include( $model_root . "dbobj.php" );
	}

	class rights extends dbobj {
		function rights() {
			dbobj::dbobj("user_rights", "userID");
		}
	}
}

$RIGHTS_OBJECT = new rights();

?>
