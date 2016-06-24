<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/12 Time: 10:43 **/
//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

//微信测试地址开关
//DEBUG_PREFIX
define('DEBUG_URL','cn');

switch(DEBUG_URL){
    case 'cn' :
        //redirect_uri
        define('OAUTH2_URI','http://mld.api.189go.cn/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://mld.api.189go.cn/access_token');

        break;
    case 'test':

        //redirect_uri
        define('OAUTH2_URI','http://mld.api.189go.test/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://mld.api.189go.test/access_token');

        break;
}

