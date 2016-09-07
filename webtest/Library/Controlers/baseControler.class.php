<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/7/28  Time: 09:21 */

namespace Controlers;
use \Controlers\urlRoute as urlRoute;
use \Controlers\urlSerial as urlSerial;

class baseControler {

    //static $serial;//序列类
    static $route;//路由类

    function __construct(){

        //echo 'baseControler OK'.'<br/>';

        //self::$serial=new urlSerial();
        self::$route=new urlRoute();

        //echo tool::jsonExit(array("uid"=>'1')) ;exit;

    }


    function initial(){

        echo 'initial OK'.'<br/>';

    }


} 