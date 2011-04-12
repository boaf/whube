<?php

	// SET THIS TO YOUR INSTALL DOMAIN
	$TLD = "localhost";
	// THIS *MUST* BE HIT-ABLE FROM OUTSIDE
	// THIS BOX. IT PREFIXES ALL THE CSS N' SHIT

	// UNLESS YOU ARE DOING DEV WORK, LOCALHOST IS
	// *WRONG* AS HELL.

	// This is where you hit the site from outside the app.
	// the HTML will be prefixed with this because of the badass
	// arg system. This is the most critical bit of the conf file.
	$SITE_PREFIX   =   "http://$TLD/whube/";

	// Some examples:
	//
	// $TLD         = "whube.com";
	// $SITE_PREFIX = "http://$TLD/"; 
	//    ^ Website only running whube @ http://whube.com/
	//
	// $TLD         = "whube.com";
	// $SITE_PREFIX = "http://bugs.$TLD/";
	//    ^ Website running whube @ http://bugs.whube.com/
	//
	// $TLD         = "domain.tld";
	// $SITE_PREFIX = "http://$TLD/whube";
	//    ^ Website running whube @ http://domain.tld/whube
	//
	//

	// This IP is the IP of the server, used by the local API.
	// Most of the time you can get away with localhost or 127.0.0.1
	// but sometimes you might have to tweek me. See how it's used over
	// in localcallback.php.

	$MY_IP         =
	array(
		"127.0.0.1", /* IPv4 */
		"localhost", /* Internal /etc/hosts */
		"::1",       /* IPv6 */
	);

	$DEBUG         =   true;
	
	include( dirname(__FILE__) . "/add-salt.php" ); // add salt to taste
?>
