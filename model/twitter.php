<?php
/**
 * Twitter class module, handles interface to twitter servers
 * 
 * Twitter class to do twitter babymaking.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @author   Thomas Martin <tenach@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */
 
if ( ! class_exists( "cacheobj" ) ) {
	// last ditch...
	$model_root = dirname(  __FILE__ ) . "/";
	include( $model_root . "cacheobj.php" );
}

if ( ! class_exists( "twitter" ) ) {
	class twitter extends cacheobj {
		var $count;
		var $friend_count;
		var $id;

		function twitter( $count = 1, $friend_count = 10 ) {
			cacheobj::cacheobj("cache", "cache_id");
			$this->count = $count;
			$this->friend_count = $friend_count;
			$model_root = basename( __FILE__ ) . "/";
			include( $model_root . "../conf/twitter.php" );
			$this->id = $id;
		}

		function showUpdates() {
			$age = $this->checkAge( "tweeter", 5 );
			if( $age <= 0 ) {
				$this->gatherTweet();
			}
			$this->getAllByID( "tweeter" );
			$cached = $this->sql->getNextRow();

			$ret = $cached["cached_contents"];
			return format ( $ret );
		}
  
	  	function gatherTweet() {
			$id     = $this->id;
			$count  = $this->count;
			$url = "http://twitter.com/statuses/user_timeline/" . $this->id;
			$BodyContent = "";
			$doc = new DOMDocument();
			$doc->load( $url );
			$items = $doc->getElementsByTagName( "item" );

			$i = 0;

			foreach( $items as $item ) {
				$titles = $item->getElementsByTagName( "title" );
				$links = $item->getElementsByTagName( "link" );
				$dates  = $item->getElementsByTagName( "pubDate" );
				$descrs = $item->getElementsByTagName( "description" );
				$title = $titles->item(0)->nodeValue;
				$link = $links->item(0)->nodeValue;
				$date = $dates->item(0)->nodeValue;
				$descr = $descrs->item(0)->nodeValue;

				$f = strpos( $title, " " );
				$owner = substr( $title, 0, $f - 1 );
				$title = substr( $title, $f );

				$retItem['title'] = $title;
				$retItem['link']  = $link;
				$retItem['date']  = $date;
				$retItem['descr'] = $descr;
				$retItem['owner'] = $owner;

				$ret[$i] = $retItem;
				++$i;
			}
			$this->updateStamp( "tweeter" );
			$this->updateCached( "tweeter", $ret[0]['descr'] );
		}
	}
}
?>
