<?php
/* Created by User:soma Worker:陈鸿扬 Date: 16/8/15  Time: 09:18 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use Debugs\frameDebug as FD;

use \Controlers\urlSerial as I;
use \Modelers\model as model;//model装载器
use \Views\view as V;

use \Https\weiApi as weiApi;//微信API
use Commons\probability as probability;//概率工具组

class activity extends baseControler {

    function signIn(){

        V::tamplate('index');

        $cArr['content']='change_signIn';
        V::asChangeArr($cArr);

        V::show();

    }

    function lottery(){

        V::tamplate('index');

        $cArr['content']='change_lottery';
        V::asChangeArr($cArr);

        V::show();

    }

    function takePhotos(){

        V::tamplate('index');

        $cArr['content']='change_takePhotos';
        V::asChangeArr($cArr);

        V::show();

    }


} 