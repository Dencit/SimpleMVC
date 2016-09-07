<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 2016/5/12 Time: 10:43 */
//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

//框架调试开关
define('FRAM_DEBUG','on');

//框架调试开关
define('PATH_URI','Path_Info');

//微信测试地址开关
//DEBUG_PREFIX
define('DEBUG_URL','pub');

switch(DEBUG_URL){
    case 'pub' :
        //redirect_uri
        define('OAUTH2_URI','http://api.bangju.com/weixin/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://api.bangju.com/weixin/access_token');

        break;
    case 'test':

        //redirect_uri
        define('OAUTH2_URI','http://smvc.somatop.test/weixinApi/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://smvc.somatop.test/weixinApi/access_token');

        break;
}



