<?php
session_start();
session_destroy();
session_start();

$_SESSION['msg'] = "See ya later!";

header( "Location: $SITE_PREFIX" );

?>
