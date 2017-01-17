<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/21  Time: 15:00 */

namespace Controlers;

use \Commons\rehomeConfig as con;
use \Modelers\model as model;//model装载器
use \Controlers\urlSerial as I;
use \Controlers\urlRoute as R;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;//跳转类

class rehomeBase extends baseControler {

    protected static $TB;
    protected static $PA;
    protected static $DB;
    protected static $FRAME;

    function __construct(){

        $con= new con();
        self::$TB=$con->data('TABLE');
        self::$PA=$con->data('PATH');
        self::$DB=$con->data('DB');
        self::$FRAME=$con->data('FRAME');

        model::init(self::$FRAME,self::$DB);//初始化model目录节点设置 和 数据库设置
        I::urlType(self::$FRAME);//序列模式设置
        R::init(self::$FRAME);//路由节点初始化

    }


    function homeDesc(){

        //管理员登录状态

        $glob_usr=@tool::get_session( array('glob_usr') )->glob_usr;
        //var_dump($glob_usr);//exit;

        $uid=@$glob_usr->uid;

        $uri=R::get('path_info');//var_dump($uri);exit;

        if(!$uid){
            switch($uri[1]){
                default:
                    jump::head('/ReHome/?/weixin/index/'); break;
                    break;
                case 'someRoute':

                    break;
            }
        }

        return $glob_usr;

        //\\
    }

    function homeDescAx(){

        //管理员登录状态

        $glob_usr=@tool::get_session( array('glob_usr') )->glob_usr;
        //var_dump($glob_usr);//exit;

        $sign=@$glob_usr->sign;
        $uid=@$glob_usr->uid;
        if($sign!=session_id()){
            tool::get_session( array('glob_usr'),1 );
            tool::jsonResult('','-1','','/ReHome/?/weixin/index/');exit;
        }

        $uri=R::get('path_info');//var_dump($uri);exit;

        if(!$uid){
            session_regenerate_id(true);
            tool::mk_session(array('glob_usr'),1);
            switch($uri[1]){
                default:
                    tool::jsonResult('','-1','','/ReHome/?/weixin/index/');exit;
                    //jump::head('/ReHome/?/weixin/index/'); break;
                    break;
                case 'someRoute':

                    break;
            }
        }

        return $glob_usr;

        //\\
    }



} 