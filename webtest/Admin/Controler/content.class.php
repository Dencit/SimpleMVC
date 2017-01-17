<?php
/* Created by User:soma Worker:陈鸿扬 Date: 16/8/15  Time: 09:18 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use \Controlers\urlSerial as I;

use \Modelers\model as model;//model装载器
use \Views\view as V;


class content extends adminBase {

    function __construct(){
        parent::__construct();//初始化 父类的构造函数
    }

    function knowledge(){

        V::tamplate('index');

        $cArr['content']='change_knowledge';
        V::asChangeArr($cArr);

        V::show();

    }

    function picturesTalk(){

        V::tamplate('index');

        $cArr['content']='change_picturesTalk';
        V::asChangeArr($cArr);

        V::show();

    }


} 