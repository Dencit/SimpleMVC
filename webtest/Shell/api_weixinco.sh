#!/bin/sh
#  api_weixin_co.sh
#  Created by 陈鸿扬 on 16/8/19.
#
# */1 * * * * /server/www/webtest/Shell/api_weixinco.sh >> /server/www/webtest/Log/api_weixinco.log 2>&1 &
# */5 * * * * /server/www/webtest/Shell/api_weixinco.sh >> /server/www/webtest/Log/api_weixinco.log 2>&1 &
#
. /etc/profile
curl http://wx.host.com/Api/?/weixinco/init/k-BangJu888/ &





