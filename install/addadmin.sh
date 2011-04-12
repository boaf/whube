#!/bin/bash

echo "OK, let's set up a user"
echo "So, you're the admin, eh?"
echo "Righto. It's on, baby."
echo ""
echo "What's your real name? ( John Q. Hacker )"
read REALNAME
echo "What's the username you want? ( jhacker )"
read USERNAME
echo "What's your email? (foo@domain.tld). This is not checked, make it right!"
read EMAIL

DATE=$(date +%s)

PWD="c21f969b5f03d33d43e04f8f136e7682" # default
PSR="foo"

echo "I'm setting your password to \"default\"."

QUERY="INSERT INTO users ( real_name, username, email, password, startstamp, trampstamp ) VALUES ('$REALNAME', '$USERNAME', '$EMAIL', '$PWD', $DATE, $DATE );"
QUERY="$QUERY\nINSERT INTO user_rights VALUES ( '1', TRUE, TRUE, TRUE, TRUE, FALSE );"

echo "OK. Sending off to DB..."

FROB="use whube;\n$QUERY"

echo "$FROB" > setup.sql

RETURN=1
while [ "x$RETURN" != "x0" ]; do
	mysql -u root -p < setup.sql
	RETURN="$?"
done
