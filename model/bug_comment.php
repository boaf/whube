<?php
/**
 * Bug class file, All bug model work lies herein
 * 
 * Base bug class to do bug CRUD work.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "bug_comment" ) ) { // if we've included twice

	if ( ! class_exists( "dbobj" ) ) { // ensure we have the superclass
	        $model_root = dirname(  __FILE__ ) . "/";
	        include( $model_root . "dbobj.php" );
	}

	class bug_comment extends dbobj {
		function bug_comment() {
			dbobj::dbobj("bug_comments", "cID"); // SQL table `bug_comments', PK `cID'
		}
	}
}

$BUG_COMMENT_OBJECT = new bug_comment();

?>
