<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/12 Time: 10:43 **/
//调试信息开关
ini_set("display_errors","On");
error_reporting(E_ALL);

//微信测试地址开关
//DEBUG_PREFIX
define('DEBUG_URL','mld');

switch(DEBUG_URL){
    case 'mld' :
        //redirect_uri
        define('OAUTH2_URI','http://mld.api.189go.cn/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://mld.api.189go.cn/access_token');

        break;
    case 'test':

        //redirect_uri
        define('OAUTH2_URI','http://smvc.somatop.test/weiWebApi/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://smvc.somatop.test/weiWebApi/access_token');

        break;
}

