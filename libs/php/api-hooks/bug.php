<?php
/*

$d['errors'] = true;
$d['success'] = false;
$d['message'] = "Unknown error";

*/

function handleBugRequest( $argv ) {

    global $BUG_OBJECT;
    global $USER_OBJECT;
    global $PROJECT_OBJECT;

	$ret     = array();
	$f       = array();
    $frobber = array();

	$ret['message'] = "";
	$ret['errors']  = true;
	$ret['success'] = false;
    
    $b    = $BUG_OBJECT;
    $bID  = $argv[1];
    
    if ( isset( $argv[1] ) ) {
    
        $b->getAllByPK( $argv[1] );
        $row = $b->getNext();
        
        // owner
        // reporter
        // project
        
        $metadata = array();
        
        if ( isset( $row['owner']    ) ) {
            $USER_OBJECT->getAllByPK( $row['owner'] );
            $owner = $USER_OBJECT->getNext();
            $metadata['owner'] = $owner['username'];
        } else {
            $metadata['owner'] = NULL;
        }
        
        if ( isset( $row['reporter'] ) ) {
            $USER_OBJECT->getAllByPK( $row['reporter'] );
            $reporter = $USER_OBJECT->getNext();
            $metadata['reporter'] = $reporter['username'];
        } else {
            $metadata['reporter'] = NULL;
        }
        
        if ( isset( $row['package']  ) ) {
            $PROJECT_OBJECT->getAllByPK( $row['package'] );
            $package = $PROJECT_OBJECT->getNext();
            $metadata['package'] = $package['project_name'];
        } else {
            $metadata['package'] = NULL;
        }

        
        if ( isset( $row['bID'] ) && ! $row['private'] ) {
        
            $frobber['bug_identifier'] = $row['bID'];
            $frobber['bug_descr']      = $row['descr'];
            $frobber['bug_title']      = $row['title'];
            
            $frobber['bug_filed']      = $row['startstamp'];
            $frobber['bug_last_touch'] = $row['trampstamp'];

            $status   = getStatus(   $row['bug_status']   );
            $severity = getSeverity( $row['bug_severity'] );
            
            $frobber['bug_status']   = $status;
            $frobber['bug_severity'] = $severity;

            $frobber['bug_meta'] = $metadata;

	        $ret['message'] = $frobber;
	        $ret['errors']  = false;
	        $ret['success'] = true;
	    } else {
        	$ret['message'] = "This bug does not exist, or it's private!";
        	$ret['errors']  = true;
        	$ret['success'] = false;
	    }
    }

	return $ret;
}

$hooks['bug'] = "handleBugRequest";

?>
