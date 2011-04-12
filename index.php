<?php
    /*
     *  License:     AGPLv3
     *  Author:      Paul Tagliamonte <paultag@whube.com>
     *  Description:
     *    Default "Hello, World" page
     */

session_start();
include ( "conf/site.php" );

header("Location: " . $SITE_PREFIX . "t/welcome" );


?>
