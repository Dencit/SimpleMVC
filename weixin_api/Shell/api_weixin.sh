#!/bin/sh

#  Created by 陈鸿扬 on 16/8/19.
#
# */1 * * * * /server/www/weixin_api/Shell/api_weixin.sh >> /server/www/weixin_api/Log/api_weixin.log 2>&1 &
# */5 * * * * /server/www/weixin_api/Shell/api_weixin.sh >> /server/www/weixin_api/Log/api_weixin.log 2>&1 &
#
. /etc/profile
curl http://wx.host.com/?k=[PASS_WORD] &





