#!/bin/sh
#
# Starts the pingback daemon used to send pingbacks and webmentions
# Author: Mark Hobson
# Date: 11/16/2010
# Description: 	Starts the pingback daemon used to send pingbacks and webmentions
#
# Include the command functions (this will import the variables PHP_BIN, DOCROOT_DIR, WEBAPP_DIR, LOG_FILE, etc)
#
PWD=`/usr/bin/dirname $0`
if [ "$PWD" == "." ];then
	PWD=`/bin/pwd`
fi
source $PWD/common.sh
#
# The following line is used for the live system to run the threshold email cron
COMMAND="$PHP_BIN $DOCROOT_DIR/daemon.php -m Daemon -a Daemon --type=Pingback --method=$1"

if [ "$2" == "--silent" ];then
	DISABLE_LOGGING="0"
else
	DISABLE_LOGGING="1"
fi
#
# Run the command by calling the execute() function defined in common.sh
#
execute
exit $?