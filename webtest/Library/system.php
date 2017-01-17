<?php
//相对于 本文件的路径
define('ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/../')) );
define('WEB_ROOT',str_replace('\\','/',realpath(dirname(__FILE__).'/../../') ) );
//一级目录
define('COMMON',ROOT."/Common");
define('ADMIN',ROOT."/Admin");
define('MODEL',ROOT."/Modeler");
define('CACHE',ROOT."/Cache");
define('LIBRARY',ROOT."/Library");
define('STATIC',ROOT."/Static");
//二级目录 - 公用设置
define('CONF',COMMON."/conf");
//二级目录 - 总库
define('LIB_3RD',LIBRARY."/Thirds");
define('LIB_COMMONS',LIBRARY."/Commons");
define('LIB_CONTROLERS',LIBRARY."/Controlers");
define('LIB_DEBUGS',LIBRARY."/Debugs");
define('LIB_FUNCTIONS',LIBRARY."/Functions");
define('LIB_HTTPS',LIBRARY."/Https");
define('LIB_MODELS',LIBRARY."/Modelers");
define('LIB_VIEWS',LIBRARY."/Views");
//



//公用设置
require_once(LIB_COMMONS.'/config.class.php');
require_once(LIB_COMMONS.'/rootProj.class.php');
//工具类
//require_once(LIB_COMMONS.'/emoji.php');
require_once(LIB_COMMONS.'/jump.php');
require_once(LIB_COMMONS.'/tool.php');
//控制器类
require_once(LIB_CONTROLERS.'/baseControler.class.php');
require_once(LIB_CONTROLERS.'/controler.class.php');
require_once(LIB_CONTROLERS.'/urlSerial.class.php');
require_once(LIB_CONTROLERS.'/urlRoute.class.php');
//测试类
require_once(LIB_DEBUGS.'/frameDebug.class.php');
require_once(LIB_DEBUGS.'/testTool.class.php');//测试工具类
//通讯类
//require_once(LIB_HTTPS.'/authApi.class.php');
//require_once(LIB_HTTPS.'/weiApi.class.php');
//数据库操作相关
require_once(LIB_MODELS.'/model.class.php');
//require_once(LIB_MODELS.'/wpDb.class.php');//绝对路径加载//相对路径容易出错
//require_once(LIB_MODELS.'/baseModel.class.php');
//require_once(LIB_MODELS.'/homeModel.class.php');
//视图类
require_once(LIB_VIEWS.'/view.class.php');




//环境设置

header("Content-type: text/html; charset=utf-8");
ini_set("magic_quotes_runtime",0);
date_default_timezone_set('Asia/Shanghai');

//过滤全局变量
$defined_vars = get_defined_vars();
foreach ($defined_vars as $key => $val) {
    if ( !in_array($key, array('_GET', '_POST', '_COOKIE', '_FILES', 'GLOBALS', '_SERVER')) ) {
        ${$key} = '';
        unset(${$key});
    }
}
unset($defined_vars);



/*
 *
#nginx 伪静态重写 例子:
#http://wx.host.com/Api/wxpay/result/a-1/b-2/c-3.html
#http://wx.host.com/Api/?/wxpay/result/a-1/b-2/c-3


        location ~ ^\/(\w+)\/(\w+)\/(\w+)$ {
            rewrite ^\/(\w+)\/(\w+)\/(\w+)$  /$1/?/$2/$3/ last;
        }
        location ~ ^\/(\w+)\/(\w+)\/(\w+)\/(|\w+[-=_\+]\w+\/|\w+[-=_\+]\w+)+(|\.\w+)$ {
            rewrite ^\/(\w+)\/(\w+)\/(\w+)\/(|\w+[-=_\+]\w+\/|\w+[-=_\+]\w+)+(|\.\w+)$  /$1/?/$2/$3/$4/ last;
        }

*/






