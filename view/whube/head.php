<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title><?php echo $TITLE; ?></title>
		<link href="<?php echo $SITE_PREFIX; ?>css/default.css" type="text/css" rel="stylesheet" ></link>
<?php

if ( isset ( $SCRIPT ) ) { // this shit right here rocks.
	echo " <!-- Automated JS Includes -->\n";
	foreach ( $SCRIPT as $key ) {
		echo "		<script src = '" . $SITE_PREFIX . "libs/js/" . $key . "' type = 'text/javascript'></script>\n";
	}
}

// and let's preload too

if ( isset ( $PRELOAD ) ) {
	echo "<!-- Let's preload -->\n<script type = 'text/javascript' >\n";
	$i = 0;
	foreach ( $PRELOAD as $key ) {
		echo "		pic$i= new Image(" . $key['w'] . ", " . $key['h'] . ");\n";
		echo "		pic$i.src=\"" . $SITE_PREFIX . "imgs/" . $key['src'] . "\";\n";
		$i++;
	}
	echo "</script>\n";
}

?>
	</head>
	<body>
<?php
if ( isset ( $TWEETER ) && $TWEETER ) {

$view_root        = dirname(  __FILE__ ) . "/";

include( $view_root . "../../model/twitter.php" );
$twit = new twitter();
$notices = $twit->showUpdates();
$owner = explode(":", $notices);  // Messy but goody
$owner = $owner[0].':';

?>
		<div class = "tweet-tweet" ><!-- I do love my tweeter -->
			<div class = "tweet-text" >
				<div class = "shim" >
<?php
	$tweet = split(":", $notices,2);
	$tweeter = '<a href="http://twitter.com/' . $tweet[0] . '">' . $tweet[0] . '</a>';
	echo $tweeter . ": " . $tweet[1];
?>
				</div>
			</div>
		</div>
<?php
}
if ( isset ( $GUILT_ME ) && $GUILT_ME ) {
?>

		<div class = "badge" ><a href = "http://donate.whube.com/" ><img src = "<?php echo $SITE_PREFIX; ?>imgs/badge.png" alt = "Join the Cause!" /></a></div>
<?php
}
?>
		<?php echo whubeNav(); ?>
		<div class = "splash" >
			<div class = "eyecandy" >
			</div>
		</div>

		<div class = "container holder" >
			<div class = "content" >
		<?php 
if ( isset( $_SESSION['err'] ) ) {
	echo "<div class = 'message growl' ><div class = 'growl-shit' ></div><div class = 'growl-content' >" . $_SESSION['err'] . "</div></div>";
	unset( $_SESSION['err'] );
} else if ( isset ( $_SESSION['msg'] ) ) { 
	echo "<div class = 'message growl' ><div class = 'growl-ok' ></div><div class = 'growl-content' >" . $_SESSION['msg'] . "</div></div>";
	unset( $_SESSION['msg'] );
}
		?>
<?php
if ( isset ( $DEBUG ) ) {
?>
<!--
Debug Frame:

Session Status:
<?php
	print_r( $_SESSION );
?>

GET Status:
<?php
	print_r( $_GET );
?>

POST Status:
<?php
	print_r( $_POST );
?>

-->

<?php
}
?>
