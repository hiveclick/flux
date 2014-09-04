#!/bin/sh
#
# Compiles the daily clicks by offer and campaign
# Author: Mark Hobson
# Date: 11/16/2010
# Description: 	Compiles the daily clicks by offer and campaign
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
COMMAND="$PHP_BIN $DOCROOT_DIR/daemon.php -m Cron -a CompileDailyClicks"

if [ "$1" == "--silent" ];then
	DISABLE_LOGGING="0"
else
	DISABLE_LOGGING="1"
fi
#
# Run the command by calling the execute() function defined in common.sh
#
execute
exit $?