#!/bin/sh
#  bangju_weixin_index.sh
#  Created by 陈鸿扬 on 16/8/19.
#
#crontab: 0 */2 *  *  * /server/shell/bangju_weixin_index.sh &
#
. /etc/profile
curl http://api.bangju.com/weixin/ &





