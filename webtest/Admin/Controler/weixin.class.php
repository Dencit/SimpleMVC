<?php
/* Created by User:soma Worker:陈鸿扬 Date: 16/8/15  Time: 09:18 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use \Controlers\urlSerial as I;

use \Modelers\model as model;//model装载器
use \Views\view as V;

use \Https\weiApi as weiApi;//微信API
use Commons\probability as probability;//概率工具组

class weixin extends adminBase {

    function __construct(){
        parent::__construct();//初始化 父类的构造函数
    }

    function keyWords(){

        V::tamplate('index');

        $cArr['content']='change_keyWords';
        V::asChangeArr($cArr);
        $lArr[0]=array('style'=>'','id'=>'1','scene'=>'1','reply'=>'1','scene_type'=>'被添加自动回复','reply_type'=>'文本','link'=>'https://www.baidu.com');
        $lArr[1]=array('style'=>'','id'=>'2','scene'=>'2','reply'=>'1','scene_type'=>'消息自动回复','reply_type'=>'文本','link'=>'https://www.baidu.com');
        $lArr[2]=array('style'=>'','id'=>'3','scene'=>'3','reply'=>'1','scene_type'=>'关键词自动回复','reply_type'=>'文本','link'=>'https://www.baidu.com');
        $lArr[3]=array('style'=>'','id'=>'4','scene'=>'3','reply'=>'2','scene_type'=>'关键词自动回复','reply_type'=>'图片','link'=>'https://www.baidu.com');
        $lArr[4]=array('style'=>'','id'=>'5','scene'=>'3','reply'=>'3','scene_type'=>'关键词自动回复','reply_type'=>'音频','link'=>'https://www.baidu.com');
        $lArr[5]=array('style'=>'','id'=>'6','scene'=>'3','reply'=>'4','scene_type'=>'关键词自动回复','reply_type'=>'视频','link'=>'https://www.baidu.com');
        $lArr[6]=array('style'=>'','id'=>'7','scene'=>'3','reply'=>'5','scene_type'=>'关键词自动回复','reply_type'=>'图文','link'=>'https://www.baidu.com');

        V::forList('keyWords',$lArr);

        V::show();

    }

    function menu(){

        V::tamplate('index');

        $cArr['content']='change_menu';
        V::asChangeArr($cArr);

        $lArr[0]=array('style'=>'info','id'=>'1','type'=>'主导航','menu'=>'导航1','link'=>'https://www.baidu.com');
        $lArr[1]=array('style'=>'','id'=>'2','type'=>'子导航','menu'=>'菜单1','link'=>'https://www.baidu.com');
        $lArr[2]=array('style'=>'info','id'=>'3','type'=>'主导航','menu'=>'导航2','link'=>'https://www.baidu.com');
        $lArr[3]=array('style'=>'','id'=>'4','type'=>'子导航','menu'=>'菜单2','link'=>'https://www.baidu.com');
        $lArr[4]=array('style'=>'info','id'=>'5','type'=>'主导航','menu'=>'导航3','link'=>'https://www.baidu.com');
        $lArr[5]=array('style'=>'','id'=>'6','type'=>'子导航','menu'=>'菜单3','link'=>'https://www.baidu.com');


        V::forList('menu',$lArr);

        V::show();

    }


} 