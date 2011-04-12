#!/bin/bash

if [ -e "../libs/js/jQuery.js" ]; then
	echo "OK. You look to have jQuery already. Remove it to re-download."
else
	if [ -e "./jQuery.js" ]; then
		rm ./jQuery.js
	fi

	wget http://code.jquery.com/jquery-latest.min.js -O jQuery.js
	mv ./jQuery.js ../libs/js/jQuery.js
fi
