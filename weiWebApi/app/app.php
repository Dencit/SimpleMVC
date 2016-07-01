<?php
defined('Author') or exit('Author: 632112883@qq.com');

ini_set("display_errors","On");
error_reporting(7);
$defined_vars = get_defined_vars();
foreach ($defined_vars as $key => $val) {
    if ( !in_array($key, array('_GET', '_POST', '_COOKIE', '_FILES', 'GLOBALS', '_SERVER')) ) {
        ${$key} = '';
        unset(${$key});
    }
}
unset($defined_vars);

define('ROOT',str_replace('\\','/',dirname(__FILE__)));
ini_set("magic_quotes_runtime",0);
date_default_timezone_set("PRC");

//redis保存session
ini_set("session.save_handler","redis");
ini_set("session.save_path","tcp://127.0.0.1:6379");

session_start();
ob_start();


//时间
define("TIME",time());
//PREFIX
define('PREFIX','mld_');

//微信测试地址开关
//DEBUG_PREFIX
define('DEBUG_URL','cn');

switch(DEBUG_URL){
    case 'cn' :
        //redirect_uri
        define('OAUTH2_URI','http://xxx.api.189go.cn/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://xxx.api.189go.cn/access_token');
        break;
    case 'test':
        //redirect_uri
        define('OAUTH2_URI','http://xxx.api.189go.test/OAuth2');
        //access_token
        define('ACCESS_TOKEN','http://xxx.api.189go.test/access_token');
        break;
}


require_once(ROOT.'/config.php');
require_once(ROOT.'/weiApi.php');









