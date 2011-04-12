<?php
/**
 * Event model stuff, handles plugin push updating
 * 
 * Event class to update remote plugins.
 * @author   Paul Tagliamonte <paultag@whube.com>
 * @version  1.0
 * @license: AGPLv3
 */
if ( ! class_exists ( "events" ) ) {

	class events {
		var $folder_root;

		function events() {
			$model_root = dirname(  __FILE__ ) . "/";
			$event_root = $model_root . "../events/"; // full of fifo
			$this->folder_root = $event_root;
		}

		function broadcast( $note ) {
			if ($handle = opendir($this->folder_root)) {
				while (false !== ($file = readdir($handle))) {
					// The "i" after the pattern delimiter indicates a case-insensitive search
					if ( $file != "." && $file != ".." ) {
						$ftest = $file;
						if (preg_match("/.*\.hook$/i", $ftest)) {
							$fh = fopen( $this->folder_root . $file, 'w');
							fwrite($fh, $note . "\n");
							fclose($fh);
						}
					}
				}
			}
		/*
		 * OK what just went on was a bit confusing. Here's what it's doing:
	         *
	         * Find all files that match "*.hook" in ../events/ ( whube/events )
	         * For each file in events ( that maches ) 
	         *   - Write to the file
	         *
		 * With a UNIX FIFO pipe, this Write will block until something
		 * ( the plugin ) reads it.
	         *
	         */
		}
	}
}

?>
