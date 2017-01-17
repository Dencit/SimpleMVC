#!/bin/sh
# Created by User: soma Worker:陈鸿扬  Date: 16/11/9  Time: 17:02
#
# */5 * * * * /server/www/webtest/Shell/system_clear.sh &
# 0 0 1 */3 * /server/www/webtest/Shell/system_clear.sh &
#

. /etc/profile

rm /server/www/webtest/Log/system.log &
