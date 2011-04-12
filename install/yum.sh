#!/bin/bash

ID=$(rpm --version)

if [ "x$?" == "x0" ]; then
	echo "You're running a rpm / yum system."
	echo "Defaulting to yum to"
	echo "install the stuff we need."
	echo "This needs sudo rights."

	sudo yum install httpd php mysql mysql-server php-mysql php-xml
fi
