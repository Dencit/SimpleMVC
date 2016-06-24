<?php
// Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/4/28 | Time: 9:45 //

header("Content-type:text/html;charset=utf-8");

$u_type=isset($_GET['u'])?$_GET['u']:'';
$ts_type=isset($_GET['ts'])?$_GET['ts']:'';
$uid_type=isset($_GET['uid'])?$_GET['uid']:'';

$hint['noData']=" 没有 access_token 或找不到当前'uid'. 如果没有 access_token请在微信登录一次非测试页，再回来用PC访问该测试页. ";
$hint['noDataEn']="no token for this ' uid ' or no found this 'uid'. if no token, please Login on WeiXinApp then Comeback to Visit on PC";


if($u_type=='Test888' && $ts_type!='' && $uid_type!=''){


    switch($ts_type){
        case 0 :
            //tool::jsonExit(array("state"=>'noDebug'));
            break;
        case 1 :

            $db=$base->wpDb;//老方式
            $usrTest=$db->get_row($db->prepare("SELECT openid,access_token FROM ".USR." WHERE uid=%s AND access_token!='' ",$uid_type));

            //新方式
            //$whereArray['uid']=$uid_type;
            //$usrTest=$base->rowSelect(USR,$whereArray);

            if($usrTest){
                $openid=$usrTest->openid;
                $access_token=$usrTest->access_token;
                $_SESSION[PREFIX.'openid']=$openid;
                $_SESSION[PREFIX.'access_token']=$access_token;
                $_SESSION[PREFIX.'uid']=$uid_type;
                //echo $openid.'||'.$access_token;
            }else{


                //旧方式
                //$usrTest = $db->get_results( $db->prepare("SELECT uid,nickname FROM ".USR." ORDER BY uid ASC") );

                //新方式
                $orderArray=array('uid','ASC');
                $selectArray=array('uid','nickname');
                $usrTest=$base->resultSelect(USR,$selectArray,'-',$orderArray);

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