#!/bin/bash

if [ "$1" != "" ]; then
	exec "$@";
	echo "Hey, I'm here!";
	exit 0;
fi

php-fpm