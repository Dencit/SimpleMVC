<?php
/* Created by User:soma Worker:陈鸿扬 Date: 16/8/15  Time: 09:18 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use \Controlers\urlSerial as I;

use \Modelers\model as model;//model装载器
use \Views\view as V;


class mall extends adminBase {

    function __construct(){
        parent::__construct();//初始化 父类的构造函数
    }

    function branch(){

        V::tamplate('index');

        $cArr['content']='change_branch';
        V::asChangeArr($cArr);

        V::show();

    }

    function goods(){

        V::tamplate('index');

        $cArr['content']='change_goods';
        V::asChangeArr($cArr);

        V::show();

    }

    function scanCode(){

        V::tamplate('index');

        $cArr['content']='change_scanCode';
        V::asChangeArr($cArr);

        V::show();

    }

    function scoresExch(){

        V::tamplate('index');

        $cArr['content']='change_scoresExch';
        V::asChangeArr($cArr);

        V::show();

    }

    function trialEntry(){

        V::tamplate('index');

        $cArr['content']='change_trialEntry';
        V::asChangeArr($cArr);

        V::show();

    }

} 