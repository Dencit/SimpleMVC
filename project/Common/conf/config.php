<?php
//环境设置

ini_set("magic_quotes_runtime",0);
date_default_timezone_set("PRC");

//过滤全局变量
$defined_vars = get_defined_vars();
foreach ($defined_vars as $key => $val) {
    if ( !in_array($key, array('_GET', '_POST', '_COOKIE', '_FILES', 'GLOBALS', '_SERVER')) ) {
        ${$key} = '';
        unset(${$key});
    }
}
unset($defined_vars);


//redis保存session

ini_set("session.save_handler","redis");
ini_set("session.save_path","tcp://127.0.0.1:6379?auth=soma5036");

ob_start();
session_start();



