#!/bin/bash

echo "OK, this is going to run the MySQL Query."
echo "This is using the user \`root'"

RETURN=1
while [ "x$RETURN" != "x0" ]; do
	mysql -u root -p < install.sql
	RETURN="$?"
done
