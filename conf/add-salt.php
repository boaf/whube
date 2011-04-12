<?php
	// OVERRIDE THE FOLLOWING IN CODE!
	$CONTENT         = "";       // Default Content
	$TITLE           = "Whube!"; // Default Title
	$THEME			 = "whube";  // Default Theme
	$SCRIPT          = array();  // Script var
	$PRELOAD         = array();  // Preload vars
	$GUILT_ME        = true;     // The donate banner. ( We're poor )
	$TWEETER         = true;     // Twitter stuff. check conf/twitter.php

	array_push( $SCRIPT, "jQuery.js");  // duh
	array_push( $SCRIPT, "effects.js"); // fade out messages etc.
?>
