<?php
/*phpinfo();exit;*/

require_once('./Common/app.php');//全局变量，路径不能用路径映射变量
require_once("./testInit.php");//pc端测试用//


$openid = isset($_SESSION[PREFIX.'openid']) ? trim($_SESSION[PREFIX.'openid']) : '';
$access_token = isset($_SESSION[PREFIX.'access_token']) ? trim($_SESSION[PREFIX.'access_token']) : '';


if(!$openid||!$access_token){
    authApi::usrAuth("http://smvc.somatop.com/project/auth.php","snsapi_userinfo");
}

$whereArray['openid']=$openid;
$user= $base->rowSelect(USR,'*',$whereArray);
//print_r($user);exit;

if(!$user){


    $data = weiApi::usrInfo($openid,$access_token);
    $globeAccessToken=authApi::globeAccessToken();
    $data_g = weiApi::subscribe($openid,$globeAccessToken);
    $info['subscribe'] = isset($data_g->subscribe)?$data_g->subscribe:'';
    $info['openid'] = $openid;
    $info['access_token']=$access_token;
    $nick= isset($data->nickname)?$data->nickname:'';
    $unified = emoji_softbank_to_unified($nick);
    $info['nickname'] = emoji_unified_to_html($unified);
    $info['sex'] = isset($data->sex)?$data->sex:'';
    $info['language'] = isset($data->language)?$data->language:'';
    $info['city'] = isset($data->city)?$data->city:'';
    $info['province'] = isset($data->province)?$data->province:'';
    $info['country'] = isset($data->country)?$data->country:'';
    $info['headimgurl'] = isset($data->headimgurl)?$data->headimgurl:'';
    $info['time'] = time();
    $ipGet=tool::get_ip();
    $info['ip']=$ipGet;


    $userInsert=$base->rowInsert(USR,$info);
    if(!$userInsert) {
        exit("fail to add user !");
    }

    $whereArray['openid']=$openid;
    $user= $base->rowSelect(USR,'*',$whereArray);
    
    $uid_get=$user->uid;
    $_SESSION[PREFIX.'uid']=$uid_get;

}

//用户有登记的情况


$uid_get=$user->uid;
$_SESSION[PREFIX.'uid']=$uid_get;
$sub_L=$user->subscribe;


if(!isset($_GET['ts'])){

    //更新微信用户资料
    $data = weiApi::usrInfo($openid,$access_token);
    $where['uid']=$_SESSION[PREFIX.'uid'];

    $dataArray['openid'] = $openid;
    $dataArray['access_token']=$access_token;

    $nick= isset($data->nickname)?$data->nickname:'';
    $unified = emoji_softbank_to_unified($nick);
    $dataArray['nickname'] = emoji_unified_to_html($unified);
    $dataArray['headimgurl'] = isset($data->headimgurl)?$data->headimgurl:'';

    $userUpdate=$base->rowUpdate(USR,$dataArray,$where);//检测到数据重复 会跳过更新


    //检查关注状态
    $globeAccessToken=authApi::globeAccessToken();
    $data_g = weiApi::subscribe($openid,$globeAccessToken);
    $sub_N= isset($data_g->subscribe)?$data_g->subscribe:'';
    //print_r($sub_L.'||'.$sub_N);
    if($sub_L!=$sub_N){

        $where['uid']=$uid_get;
        $dataArray['subscribe']=$sub_N;
        $usr_sub_up=$base->rowUpdate(USR,$dataArray,$where);
        if(!$usr_sub_up){
            exit("fail to add subscribe !");
        }

    }
}

jump::head("./Public/index.html");

