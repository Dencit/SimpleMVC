<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/21  Time: 15:00 */

namespace Controlers;

use \Commons\adminConfig as con;
use \Modelers\model as model;//model装载器
use \Controlers\urlSerial as I;
use \Controlers\urlRoute as R;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;//跳转类

class adminBase extends baseControler {

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


    function adminDesc(){

        //管理员登录状态

        $uri=R::get('path_info');

        $glob_usr=@tool::get_session( array('glob_usr') )->glob_usr;

        //register_shutdown_function( array($this, "check_abort") );

        //var_dump($glob_usr->sign);//exit;
        //var_dump(session_id());

        //exit;

        $sign=@$glob_usr->sign;
        $uid=@$glob_usr->uid;
        $type=@$glob_usr->type;
        $ad_type=@$glob_usr->ad_type;

        if($sign!=session_id()){
            tool::get_session( array('glob_usr'),1 );
            jump::alertTo('身份过期,请重新登录','/Admin/?/login/in/'); exit;
        }

        if(!$uid){ jump::alertTo('身份过期,请重新登录','/Admin/?/login/in/'); exit; }

        if($ad_type=='1'){
            tool::get_session( array('glob_usr'),1 );
            jump::alert('授权成功,您可在企业号应用中点餐!'); exit;
        }

        //var_dump($uri);

        if($ad_type==2&&$uri[1]!='scangun'){

            jump::alertTo('收银员 无权限访问!','back');
        }

        return $glob_usr;

        //\\
    }


} 