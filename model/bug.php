<?php
/**
 * Bug class file, All bug model work lies herein
 * 
 * Base bug class to do bug CRUD work.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "bug" ) ) { // if we've included twice

	if ( ! class_exists( "dbobj" ) ) { // ensure we have the superclass
	        $model_root = dirname(  __FILE__ ) . "/";
	        include( $model_root . "dbobj.php" );
	}

	include ( $model_root . "user.php" );     // we need the user
	include ( $model_root . "project.php" );  // and project models

	class bug extends dbobj {
		function bug() {
			dbobj::dbobj("bugs", "bID"); // SQL table `bugs', PK `bID'
			// this just calls the super-class's constructor
			// this tells the dbobj sutff to use the the 
			// `bugs' table, with a primary key of `bID'.
		}
		// Let's add in some functionallity for user stuff.
		function getOwner( $bID ) {
			$this->getAllByPK( $bID );	// get everything
							// that matches the PK
							// being = to $bID
			$row = $this->getNext();
							// get the next (and hopefully
							// only ) row
			$u = new user();
			$u->getAllByPK( $row['owner'] );
			return $u->getNext(); // ( first row :P )
		}
		function getReporter( $bID ) {
			$this->getAllByPK( $bID );
			$row = $this->getNext();
			$u = new user();
			$u->getAllByPK( $row['reporter'] );
			return $u->getNext(); // ( first row :D )
		}
		function getProject( $pID ) {
			$this->getAllByPK( $pID );
			$row = $this->getNext();
			$p = new project();
			$p->getAllByPK( $row['package'] );
			return $p->getNext(); // ( first row :) )
		}
	  
		function getAllBugs() {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "bugs ORDER BY bID DESC;" );
			$ret = array();
			while ( $row = $sql->getNextRow() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}
	  
	}
}
$BUG_OBJECT = new bug();
?>
