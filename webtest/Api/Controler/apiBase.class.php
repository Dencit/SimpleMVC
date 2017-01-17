<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/21  Time: 15:00 */

namespace Controlers;

use \Commons\apiConfig as con;
use \Modelers\model as model;//model装载器
use \Controlers\urlSerial as I;
use \Controlers\urlRoute as R;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;//跳转类

use \NoSql\redis as redis;//noSql redis类
use \Apis\weicoApi as weiApi;//企业号接口类

class apiBase extends baseControler {

    protected static $FRAME;

    protected static $DB;
    protected static $TB;
    protected static $PA;

    protected static $RED;
    protected static $REDIS;

    protected static $WX;
    protected static $WEIXIN;


    function __construct(){

        $con= new con();
        self::$FRAME=$con->data('FRAME');

        self::$DB=$con->data('DB');
        self::$TB=$con->data('TABLE');
        self::$PA=$con->data('PATH');

        self::$REDIS=$con->data('REDIS');
        self::$RED=new redis(self::$REDIS);

        self::$WEIXIN=$con->data('WX_CO');
        self::$WX=new weiApi(self::$WEIXIN);

        model::init(self::$FRAME,self::$DB);//初始化model目录节点设置 和 数据库设置
        I::urlType(self::$FRAME);//序列模式设置
        R::init(self::$FRAME);//路由节点初始化

    }


    function apiDesc(){

        //登录状态

        $glob_usr=@tool::get_session( array('glob_usr') )->glob_usr;
        //var_dump($glob_usr);//exit;

        $sign=@$glob_usr->sign;
        $uid=@$glob_usr->uid;
        if($sign!=session_id()){
            tool::get_session( array('glob_usr'),1 );
            tool::jsonResult('','-1','','/Home/?/weixin/index/');exit;
        }

        $uri=R::get('path_info');//var_dump($uri);exit;

        if(!$uid){
            session_regenerate_id(true);
            tool::mk_session(array('glob_usr'),1);
            switch($uri[1]){
                default:
                    tool::jsonResult('','-1','','/Home/?/weixin/index/');exit;
                    //jump::head('/Home/?/weixin/index/'); break;
                    break;
                case 'someRoute':

                    break;
            }
        }

        return $glob_usr;

        //\\
    }



} 