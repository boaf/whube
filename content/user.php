<?php
	$p = $PROJECT_OBJECT;
	$b = $BUG_OBJECT;
	$u = $USER_OBJECT; // precretaed objects

	$u->getByCol( "username", $argv[1] ); // get the /t/%s
	$user = $u->getNext(); // get the first result.
	if(!$user || $user == "") {
		$_SESSION['err'] = $argv[1] . " isn't registered!";
		header( "Location: $SITE_PREFIX" . "t/home" );
		exit(0);
	}

	$RIGHTS_OBJECT->getAllByPK( $user['uID'] ); // get rights for the user
	$user_rights = $RIGHTS_OBJECT->getNext();


	$critical = $b->specialSelect( "bug_status != 1" );

	if ( isset ( $user["username"] ) ) {
		$TITLE = $user["username"] . " | Whube";
		$CONTENT = "
		<h1>" . $user["username"] . "</h1>
		<p>This here be " . $user["realname"] . "</p>
		";
	} else {
		$_SESSION['err'] = "User " . $argv[1] . " does not exist!";
		header( "Location: $SITE_PREFIX" . "t/home" );
		exit(0);
	}

?>
