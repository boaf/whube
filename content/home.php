<?php

include( "model/user.php" );
include( "libs/php/core.php" );

useScript("sorttable.js");
useScript("tablehover.js");

requireLogin();

$TITLE    = "Welcome Home!";
$CONTENT  = "<br /><h1>Heyya, " . $_SESSION['real_name'] . "</h1>Welcome Home.<br />\n";

?>
