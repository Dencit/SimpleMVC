<?php
/* Created by User: soma Worker: 陈鸿扬  Date: 16/7/28  Time: 09:27 */

require_once('../Library/system.php');


////继承补充

    require_once(LIB_COMMONS.'/probability.class.php');//概率工具组
    require_once(LIB_3RD.'/aliDayu.php');//大于短信类

    require_once(LIB_COMMONS.'/emoji.php');//微信默认表情转换
    require_once(LIB_HTTPS.'/authApi.class.php');//微信api
    require_once(LIB_HTTPS.'/weiApi.class.php');//微信api

    require_once(LIB_MODELS.'/WpDb.class.php');//wpDB
    require_once(LIB_MODELS.'/WpBaseModel.class.php');//wpDB ORM

    require_once(LIBRARY.'/NoSql/RedisDB.class.php');//redis 模拟数据库
    require_once(LIB_MODELS.'/PdoDB.class.php');//pdo
    require_once(LIB_MODELS.'/RePdoBaseModel.class.php');//redis pdo ORM

    require_once(ROOT.'/ReHome/Common/rehomeConfig.class.php');
    require_once(ROOT.'/ReHome/Controler/rehomeBase.class.php');
    require_once(ROOT.'/ReHome/Modeler/rehomeBase.class.php');


//\\

    //调试信息开关
    ini_set("display_errors","On");
    error_reporting(E_ALL);

    //session 前缀
    define('PREFIX','test_');
    //框架调试开关
    define('FRAM_DEBUG','on');

    //redis保存session
    ini_set("session.save_handler","redis");
    ini_set("session.save_path","tcp://127.0.0.1:6379?auth=bangju2015");
    ob_start();
    session_start();


////框架设置


    $frameConfig=new \Commons\rehomeConfig();
    $FRAME=$frameConfig->data('FRAME');

    $controler=new \controlers\controler($FRAME);
    $controler->listenUri();//开始侦听url路由参数,加载 controler 虚拟页面


//\\框架设置

