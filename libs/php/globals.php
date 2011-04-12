<?php
  /*
   *  License:   AGPLv3
   *  Author:    Paul Tagliamonte <paultag@whube.com>
   *  Description:
   *    Global Routines
   */

$php_root = dirname(__FILE__ ) . "/";

include( $php_root . "../../model/sql.php" );
include( $php_root . "../../model/user.php" );
include( $php_root . "../../model/bug.php" );
include( $php_root . "../../model/project.php" );

$JS_ROOT = $php_root . "../js/";

function useScript( $id ) {
	global $SCRIPT, $JS_ROOT;
	if ( file_exists( $JS_ROOT . $id ) ) {
		array_push( $SCRIPT, $id );
	}
}

function sendEmail( $from, $to, $subject, $message ) {
	$headers = 'From: ' . $from . "\r\n";
	mail($to, $subject, $message, $headers);
//	echo $to, $subject, $message, $headers;
//	exit(0);
}

function clean( $ret ) {
	if ( isset( $ret ) ) {
		$ret = addslashes( $ret );
		return htmlentities( $ret, ENT_QUOTES);
	} else {
		return NULL;
	}
}

function format( $ret ) {
	if ( isset( $ret ) ) {
		$ret = stripslashes( $ret );
		$ret = html_entity_decode( $ret, ENT_QUOTES);
		return $ret;
	} else {
		return NULL;
	}
}

function excerpt( $ret, $numWords = 10, $trail = '...' ) {
	if ( isset( $ret ) ) {
		$words = explode( ' ', $ret );
		if ( count ( $words ) > $numWords && $numWords > 0 ) {
			$ret = implode ( ' ', array_slice ( $words, 0, $numWords ) ) . '...';
		}
		return $ret;
	}
}

function preload( $l, $w, $src ) {
	// XXX: Meh. Remove this post DD.
}

function breakUpLine( $line ) {
	$pos = strrpos($line, "/");
	if ($pos === false) {
		return array($line);
	} else {
		$prefix  = trim(substr( $line, 0, $pos ) );
		$postfix = trim(substr( $line, $pos + 1, strlen( $line )) );
		$prefix  = htmlentities( $prefix,  ENT_QUOTES);
		$postfix = htmlentities( $postfix, ENT_QUOTES);

		$ret = array( $prefix, $postfix );
	}
	return $ret;
}

function requireLogin() {
	global $SITE_PREFIX;
	global $argv;

	$argl = "";

	for ( $i = 0; $i < sizeof( $argv ); ++$i ) {
		$argl .= $argv[$i] . "/";
	}

	if ( isset ( $_SESSION['id'] ) && $_SESSION['id'] > 0) {
		return true;
	} else {
		$_SESSION['err'] = "Login before you can hit that page!";
		header("Location: " . $SITE_PREFIX . "t/login/" . $argl );
		exit(0);
	}
}

function requireLocalIP() {
	global $SITE_PREFIX;
	global $MY_IP;

	$LOCAL = false;

	foreach ( $MY_IP as $KEY ) {
		if ( $_SERVER['REMOTE_ADDR'] == $KEY ) {
			$LOCAL = true;
		}
	}

	if ( $LOCAL ) {
		return true;
	} else {
		$_SESSION['err'] = "You're not local. Error.";
		header("Location: " . $SITE_PREFIX . "t/home" );
		exit(0);
	}


}

function checkBugViewAuth( $bugID, $requester ) {

$b = new bug();
$u = new user();
$p = new project();

$b->getAllByPK( $bugID );
$bug = $b->getNext();

if ( isset( $bug['bID'] ) ) {
	if ( isset($_SESSION['patrick_stewart']) && $_SESSION['patrick_stewart'] ) { // see gate for context
		return array( true, $bug['private'] ); // public bug, dummy
	}

	$whoami = $requester;

	if ( $bug['private'] ) {
		// good query.
		$u->getAllByPK( $bug['owner'] );
		$owner = $u->getNext();
		$u->getAllByPK( $bug['reporter'] );
		$reporter = $u->getNext();
		$p->getAllByPK( $bug['package'] );
		$project = $p->getNext();

		$oid = -10000;
		$rid = -10000;
		$pid = -10000;

		if ( isset ( $owner['uID'] ) )    { $oid = $owner['uID'];    }
		if ( isset ( $reporter['uID'] ) ) { $rid = $reporter['uID']; }
		if ( isset ( $project['oID'] ) )  { $pid = $project['oID'];  }

		if (
			$oid != $whoami &&
			$rid != $whoami &&
			$pid != $whoami
		) {
			return array( false, $bug['private'] );
		} else {
			return array( true, $bug['private'] );
		}

	} else {
		return array( true, $bug['private'] ); // public bug, dummy
	}
} else {
	return array( false, false ); // bug iz no good
}

/* 

if bug.private:
	check if is owner
	check if is reporter
	check if is asignee
	check if is project owner
	check if site administrator / staff

	any of the above: Yes, otherwise, no
else:
	Yes


Query bug, if it's public, don't give a shit.


*/

}

function loggedIn() {
	if ( isset ( $_SESSION['id'] ) && $_SESSION['id'] > 0 ) {
		return true;
	} else {
		return false;
	}
}

function getProjectName( $pID ) {
	return executeGet( "projects", "pID", $pID );
}

function getStatus( $status ) {
	return executeGet( "status", "statusID", $status );
}

function getAllStatus() {
	return executeGetAll( "status" );
}

function getSeverity( $severity ) {
	return executeGet( "severity", "severityID", $severity );
}

function getAllSeverity() {
	return executeGetAll( "severity" );
}

function getRights( $id ) {
	return executeGet( 'user_rights', 'userID', $id );
}

/*
 * Base Retrieval Functions 
 */
function executeGet( $table_name, $pkcol, $value ) {
	if ( isset ( $value ) ) {
		global $TABLE_PREFIX;
		$sql = new sql();
		$sql->query( "SELECT * FROM " . $TABLE_PREFIX . $table_name . " WHERE " . $pkcol . " = " . $value . ";" );
		$ret = $sql->getNextRow();
		return $ret;
	}
}

function bugList( $count, $resource ) {
	$ret = "";
	$ret .= "
<table class = 'sortable' >
	<tr class = 'nobg' >
		<th>ID</th> <th> Status </th> <th> Severity </th> <th>Owner</th> <th>Project</th> <th>Private</th> <th>Title</th>
	</tr>
";

global $PROJECT_OBJECT;
global $USER_OBJECT;
global $BUG_OBJECT;
global $SITE_PREFIX;

$p = $PROJECT_OBJECT;
$u = $USER_OBJECT;
$b = $BUG_OBJECT;

$p->getAll();
$u->getAll();
$b->getAll();

$s = 0;

$bugs = $resource;
$bCount = count($bugs);

while ( $s < $bCount ) {
	$row = $bugs[$s];
	$b->getAllByPK( $row['bID'] );
	$u->getAllByPK( $row['owner'] );

	$u->getByCol( 'uID', $row['owner'] );
	$user = $u->getNext();

	$p->getByCol( 'pID', $row['package'] );
	$project = $p->getNext();

	if ( $row['bug_status'] == 8 ) {
		$ret .= "";
		$s++;
	} else {

		if ( $user == '' ) {
			$user = '-';
		}

		if ( $row['private'] == 1 ) {
			$private = "Yep";
		} else {
			$private = "No";
		}


		$status   = getStatus(   $row['bug_status']   );
		$severity = getSeverity( $row['bug_severity'] );

		$statusClass   = "goodthings";
		$severityClass = "goodthings";

		$overrideOne = False;
		$overrideTwo = False;

		if ( $status['critical'] ) {
			$statusClass = "badthings";
		}

		if ( $severity['critical'] ) {
			$severityClass = "badthings";
		}

		if ( strpos ( $row['title'], ' ' ) ) {
			$bugLink = str_replace ( ' ', '-', $row['title'] );
		} else {
			$bugLink = clean($row['title']);
		}

		$ret .= "\t<tr style=\"cursor:pointer\" onclick=\"document.location.href = '" . $SITE_PREFIX . "t/bug/" . $row['bID'] . "'\" >\n<td>" .
			$row['bID'] . "</td><td class = '" . $statusClass . "' >" . $status['status_name'] .
			"</td><td class = '" . $severityClass . "'>" . $severity['severity_name'] .
			"</td><td>" . $user['real_name'] . "</td>
			<td>" . $project['project_name'] . "</td>
			<td>" . $private  . "</td>
			<td><a href='" . $SITE_PREFIX . "t/bug/" . $row['bID'] . "/" . $bugLink . "'>" . $row['title'] . "</a></td>\n\t</tr>\n";
		$s++;
	}
}

$ret .= "
</table><br /><br />
";

return $ret;

}

function executeGetAll( $table_name ) {
	global $TABLE_PREFIX;
	$sql = new sql();
	$sql->query( "SELECT * FROM " . $TABLE_PREFIX . $table_name . ";" );
	$ret = array();
	while ( $row = $sql->getNextRow() ) {
		array_push( $ret, $row );
	}
	return $ret;
}

?>
