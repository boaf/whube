#!/bin/bash

ID=$(dpkg --version)

if [ "x$?" == "x0" ]; then
	echo "You're running a dpkg system."
	echo "Defaulting to apt-get to"
	echo "install the stuff we need."
	echo "This needs sudo rights."

	sudo apt-get install apache2 php5 php5-mysql mysql-server php5-cli
fi
