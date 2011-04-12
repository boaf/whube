<?php

include( dirname(__FILE__) . "/../../conf/site.php" );
include( dirname(__FILE__) . "/../php/core.php" );

$TERMSTRING = "WHUBIX";
$TERMSTRING .= "/$VERSION";


?>$(document).ready(function() {
	$(".term").terminal(
		"<?php echo $SITE_PREFIX; ?>libs/php/term.php",
		{
			'hello_message'    : "<?php echo $TERMSTRING; ?> LOADED",
			'custom_prompt'    : "$ ",
		}
	);
});
