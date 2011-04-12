<?php

$js_root	= dirname(  __FILE__ ) . "/";

include( $js_root . "../../conf/site.php" );
include( $js_root . "../../libs/php/globals.php" );


header( "Content-type: text/javascript" );

$PROJECT_FINDER = $SITE_PREFIX . "libs/php/validate-user.php";

$SCRIPT= <<<EOF
var userLastPressed = new Date();
userLastPressed.setDate(0);
userFetch();

function userHandleLoading() {
	userLastPressed = new Date();
	userFetch();
}

function userFetch() {
	var t = new Date();
	if ( userLastPressed.getTime() + 800 < t.getTime() ) {
		userLastPressed = new Date();
		userValidate();
	} else {
		setTimeout( userFetch, 10 );
	}
}

function userValidate() {
	tex = $('#user').val();
	$.getJSON("$PROJECT_FINDER?p=" + tex,function(data){
EOF;

$SCRIPT .= "
		if ( data.success ) {
			$('#user-ok').html('<img src = \"" . $SITE_PREFIX . "imgs/ok.png\" alt = \"\" />');
			$('#user').removeClass(\"shit\");
			$('#user').removeClass(\"meh\");
			$('#user').addClass(\"ok\");
			$('#user-descr').html( data.descr );
		} else {
			$('#user-ok').html('<img src = \"" . $SITE_PREFIX . "imgs/no.png\" alt = \"\" />');
			$('#user').removeClass(\"ok\");
			$('#user').removeClass(\"meh\");
			$('#user').addClass(\"shit\");
			if ( data.bestmatch ) {
				$('#user-descr').html( \"Did you mean: \" + data.bestmatch + \"?\" );
			} else {
				$('#user-descr').html( \"I have no goddamn clue what you are talking about.\" );
			}
		}
	});
}
$(window).load( function() {
	$('#user').keyup(function(event) {
		$('#user-ok').html('<img src = \"" . $SITE_PREFIX . "imgs/loading.png\" alt = \"\" />');
		$('#user').removeClass(\"ok\");
		$('#user').removeClass(\"shit\");
		$('#user').addClass(\"meh\");
		userHandleLoading();
	});
	userValidate();
});

";

echo $SCRIPT;

?>
