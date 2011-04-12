<?php
/**
 * project class file, All project model work lies herein
 * 
 * Base project class to do bug CRUD work.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */

if ( ! class_exists ( "project" ) ) {

	if ( ! class_exists( "dbobj" ) ) {
		// last ditch...
		$model_root = dirname(  __FILE__ ) . "/";
		include( $model_root . "dbobj.php" );
	}

	class project extends dbobj {
		function project() {
			dbobj::dbobj("projects", "pID");
		}

		function hasRights( $user, $project ) {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "project_members WHERE projectID = \"$project\" AND userID = \"$user\";" );

			$row = $sql->getNextRow();

			if ( $row == NULL )
				return NULL;

			return $row['active'];
		}

		function userMembership( $id ) {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "project_members WHERE projectID = " . $id . ";" );
			$ret = array();
			while ( $row = $sql->getNextRow() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}

		function addToTeam( $user, $project ) {
			global $TABLE_PREFIX;
			$sql = new sql();
			$teamski = $this->hasRights( $user, $project );
			if ( $teamski == NULL ) {
				$sql->query( "INSERT INTO project_members VALUES ('"
					. $user . "', '" . $project . "', TRUE, '"
					. time() . "', '" . time() . "');"
				);
			}
			if ( $teamski ) {
				return;
			} else {
				$sql->query( "UPDATE project_members SET active = TRUE WHERE userID = '" . $user . "' AND projectID = '" . $project . "';" );
			}
		}

		function removeFromTeam( $user, $project ) {
			global $TABLE_PREFIX;
			$sql = new sql();
			$teamski = $this->hasRights( $user, $project );
			if ( $teamski == NULL )
				return;
			if ( $teamski )
				$sql->query( "UPDATE project_members SET active = FALSE WHERE userID = '" . $user . "' AND projectID = '" . $project . "';" );
		}


		function getName( $id ) {
			$this->getAllByPK( $id );
			$ret = $this->getNext();
			return $ret;
		}

		function getAllProjects() {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT * FROM " . $TABLE_PREFIX . "projects;" );
			$ret = array();
			while ( $row = $sql->getNextRow() ) {
				array_push( $ret, $row );
			}
			return $ret;
		}
		
		function getAllTeams() {
			global $TABLE_PREFIX;
			$sql = new sql();
			$sql->query("SELECT DISTINCT * FROM " . $TABLE_PREFIX . "project_members;" );
			$ret = array();
			while ( $row = $sql->GetNextRow() );
		}
		
	}
}

$PROJECT_OBJECT = new project();

?>
