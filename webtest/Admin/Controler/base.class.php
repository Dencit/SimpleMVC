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

class base extends baseControler {

    function test(){

        //test/uid-10/sid-11

        V::tamplate('index');//加载 include 嵌套标签指向文件 到当前模板

        $cArr['admin']='change_admin';
        V::asChangeArr($cArr);//实时替换 可变模板 标记//数组方式

        $I=I::get();
        $get['uid']=$I->uid;
        $get['sid']=$I->sid;
        V::asSignArr($get);//变量填充 当前模板中 单个标记//数组方式

        V::show();//输出视图

    }

    function welcome(){

        V::tamplate('index');

        $cArr['content']='change_welcome';
        V::asChangeArr($cArr);

        V::show();//输出视图

    }

    function admin(){

        V::tamplate('index');

        $cArr['content']='change_admin';
        V::asChangeArr($cArr);

        $lArr[0]=array('aid'=>'1','admin'=>'admin1','auth'=>'超级','read'=>'说明');
        $lArr[1]=array('aid'=>'2','admin'=>'admin2','auth'=>'普通','read'=>'说明');
        $lArr[2]=array('aid'=>'3','admin'=>'admin3','auth'=>'普通','read'=>'说明');
        $lArr[3]=array('aid'=>'4','admin'=>'admin4','auth'=>'普通','read'=>'说明');
        $lArr[4]=array('aid'=>'5','admin'=>'admin5','auth'=>'普通','read'=>'说明');

        V::forList('admin',$lArr);

        V::show();

    }

    function member(){

        V::tamplate('index');

        $cArr['content']='change_member';
        V::asChangeArr($cArr);

        $lArr[0]=array('uid'=>'1','nickname'=>'nickname1','scores'=>'233','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[1]=array('uid'=>'2','nickname'=>'nickname2','scores'=>'453','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[2]=array('uid'=>'3','nickname'=>'nickname3','scores'=>'865','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[3]=array('uid'=>'4','nickname'=>'nickname4','scores'=>'322','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[4]=array('uid'=>'5','nickname'=>'nickname5','scores'=>'963','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');

        V::forList('member',$lArr);

        V::show();

    }

    function worker(){

        V::tamplate('index');

        $cArr['content']='change_worker';
        V::asChangeArr($cArr);

        $lArr[0]=array('wid'=>'1','nickname'=>'nickname1','name'=>'name1','deeds'=>'233','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[1]=array('wid'=>'2','nickname'=>'nickname2','name'=>'name2','deeds'=>'453','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[2]=array('wid'=>'3','nickname'=>'nickname3','name'=>'name3','deeds'=>'865','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[3]=array('wid'=>'4','nickname'=>'nickname4','name'=>'name4','deeds'=>'322','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');
        $lArr[4]=array('wid'=>'5','nickname'=>'nickname5','name'=>'name5','deeds'=>'963','register'=>'2016-07-08 00:00:00','login'=>'2016-07-08 00:00:00');

        V::forList('worker',$lArr);

        V::show();

    }


} 