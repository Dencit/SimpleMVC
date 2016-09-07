<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/8/1  Time: 15:42 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use \Controlers\urlSerial as I;

use \Modelers\model as model;//model装载器
use \Https\weiApi as weiApi;


class weixin extends baseControler {

    private static $u_type;
    private static $ts_type;
    private static $uid_type;

    private static $openid;
    private static $access_token;

    private static $weiApi;


    function __construct(){
        new parent;

        $s_get=I::get();//侦听url序列

        //var_dump($s_get); exit;

        self::$u_type=isset($s_get->u)?$s_get->u:'';
        self::$ts_type=isset($s_get->ts)?$s_get->ts:'';
        self::$uid_type=isset($s_get->uid)?$s_get->uid:'';//序列引用


        model::init('weixin');//一个 controler 对应一个 同名 modeler
        model::set();//加载 index modeler 可同时传参给 构造函数

        //self::$weiApi=new weiApi();

        self::$openid = isset($_SESSION[PREFIX.'openid']) ? trim($_SESSION[PREFIX.'openid']) : '';
        self::$access_token = isset($_SESSION[PREFIX.'access_token']) ? trim($_SESSION[PREFIX.'access_token']) : '';


    }

    function testInit()//伪装验证
    {

        unset( $_SESSION[PREFIX.'openid'] );//清除当前session openid
        unset( $_SESSION[PREFIX.'access_token'] );//清除当前session access_token

        //session_destroy();//

        $MS=model::set();

        $u_type=self::$u_type;
        $ts_type=self::$ts_type;
        $uid_type=self::$uid_type;//序列引用
        //echo $u_type.$ts_type.$uid_type;//

        $hint['noData']=" 没有 access_token 或找不到当前'uid'. 如果没有 access_token请在微信登录一次非测试页，再回来用PC访问该测试页. ";
        $hint['noDataEn']="no token for this ' uid ' or no found this 'uid'. if no token, please Login on WeiXinApp then Comeback to Visit on PC";


        if($u_type=='Test888' && $ts_type!='' && $uid_type!='')
        {

            switch($ts_type){
                case 0 :
                    tool::jsonExit(array("state"=>'noDebug'));
                    break;
                case 1 :

                    //旧方式
                    /*$db=$MS::$wpdb;
                    $usrTest=$db->get_row($db->prepare("SELECT openid,access_token FROM ".USR." WHERE uid=%s AND access_token!='' ",$uid_type));*/

                    //新方式
                    $whereArray['uid']=$uid_type;
                    $usrTest=$MS->rowSelect(USR,'*',$whereArray);
                    //var_dump($usrTest);exit;//

                    if($usrTest){
                        $openid=$usrTest->openid;
                        $access_token=$usrTest->access_token;
                        $_SESSION[PREFIX.'openid']=$openid;
                        $_SESSION[PREFIX.'access_token']=$access_token;
                        $_SESSION[PREFIX.'uid']=$uid_type;
                        //echo $_SESSION[PREFIX.'openid'].'<br/>'.$_SESSION[PREFIX.'access_token'];exit;//

                        jump::head("./?/weixin/index/u-Test888/ts-1/uid-".$uid_type."/");//成功跳转index

                    }else{


                        //旧方式
                        /*$db=$MS::$wpdb;
                        $usrTest = $db->get_results( $db->prepare("SELECT uid,nickname FROM ".USR." ORDER BY uid ASC") );*/

                        //新方式
                        $orderArray=array('uid','ASC');
                        $selectArray=array('uid','nickname');
                        $usrTest=$MS->resultSelect(USR,$selectArray,'-',$orderArray);

                        echo "| UID || NICK_NAME |"."<br/>";
                        foreach($usrTest as $k=>$v){
                            echo "| ".$usrTest[$k]->uid." || ".$usrTest[$k]->nickname." |"."<br/>";
                        }
                        echo $hint['noData']."<br/>";
                        tool::jsonExit($hint['noDataEn']);

                    }

                    //tool::jsonExit(array("state"=>'debug'));
                    break;
            }

        }


    }

    function index(){

        $I=I::get();//侦听url序列
        $sid_get=isset($I->sid)?$I->sid:'';//侦听url中的sid序列单元
        //var_dump($sid_get);exit;//

        $MS=model::set();
       // $weiApi=self::$weiApi;
        $openid=self::$openid;
        $access_token=self::$access_token;
        //var_dump($openid);var_dump($access_token);exit;//

        //$openid=true;$access_token=true;//
        if(!$openid||!$access_token)//没有$openid和$access_token的情况
        {
            switch ($sid_get!=''){
                default:
                    //exit('http://wx.bangju.com/project/?/weixin/auth/');//
                    weiApi::usrAuth("http://wx.bangju.com/Home/?/weixin/auth/","snsapi_userinfo");
                    break;
                case true :
                    //exit("http://wx.bangju.com/project/?/weixin/auth/sid-".$sid_get);//
                    weiApi::usrAuth("http://wx.bangju.com/Home/?/weixin/auth/sid-".$sid_get."/","snsapi_userinfo");
                    break;
            }
        }


        $u_type=self::$u_type; $ts_type=self::$ts_type; $uid_type=self::$uid_type;//序列引用
        if($u_type=='' && $ts_type=='' && $uid_type=='')//testInit序列判断
        {

                $data = weiApi::usrInfo($openid, $access_token);
                $globeAccessToken = weiApi::globeAccessToken();
                $data_g = weiApi::subscribe($openid, $globeAccessToken);
                //var_dump($data);echo"<br/>";var_dump($data_g);exit;//

                $dataArray = weiApi::userInfo($data, $data_g);//获取用户数据
                $dataArray['openid'] = $openid;
                $dataArray['access_token'] = $access_token;
                $dataArray['time'] = time();
                $ipGet = tool::get_ip();
                $dataArray['ip'] = $ipGet;
                //print_r($dataArray);exit;//

                $whereArray['openid'] = $openid;
                $user = $MS->rowSelect(USR, '*', $whereArray);//筛选
                //var_dump($user);exit;//

                if (!$user)//用户无登记的情况
                {

                    $userInsert = $MS->rowInsert(USR, $dataArray);
                    if (!$userInsert) {
                        exit("fail to add user !");
                    }

                    $user = $MS->rowSelect(USR, '*', $whereArray);
                    $_SESSION[PREFIX . 'uid'] = $user->uid;//session 存入uid
                    //exit($_SESSION[PREFIX.'uid']);//
                } else //用户有登记的情况
                {

                    //更新微信用户资料
                    $userUpdate = $MS->rowUpdate(USR, $dataArray, $whereArray);//检测到数据重复 会跳过更新

                    $_SESSION[PREFIX . 'uid'] = $user->uid;
                    //exit($_SESSION[PREFIX . 'uid']);//

                }


        }


        switch ($sid_get!=''){
            default:
                jump::head("./Public/index.html");
                break;
            case true :

                //如果用户访问自己的分享链接 就跳到首页。
/*                $uid_get=$_SESSION[PREFIX.'uid'];
                if($sid_get==$uid_get){
                    jump::head("./Public/index.html");
                }*/

                jump::head("./Public/share.html#share=&sid=".$sid_get);

                break;
        }

    }

    function auth(){

        $openid = isset($_GET['openid']) ? trim($_GET['openid']) : '';
        $access_token = isset($_GET['access_token']) ? trim($_GET['access_token']) : '';
        //var_dump($openid);exit;//

        if (!$openid || !$access_token){
            exit('fail to get openid or access_token.');
        }


        $_SESSION[PREFIX.'openid'] = $openid;
        $_SESSION[PREFIX.'access_token'] = $access_token;

        //print_r($_SESSION[PREFIX.'openid']."<br/>".$_SESSION[PREFIX.'access_token']); exit;


        $I=I::get();
        $sid_get=isset($I->sid)?$I->sid:'';

        if($sid_get!=''){
            jump::head("./?/weixin/index/sid-".$sid_get);
        }else{
            jump::head("./?/weixin/index/");
        }


    }



} 