<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 2016/6/2 Time: 11:21 */
namespace Https;

class authApi{

    private static $account = "【可以访问二级api的帐号】";
    private static $passwd = "【可以访问二级api的密码】";

    function __construct(){

    }

//有限授权
     static function usrAuth($redirect_uri = '',$scope = 'snsapi_userinfo'){
        $time = time();
        $sign = "OAuth2".self::$account.self::$passwd.$time;
        $url=OAUTH2_URI."/?acc=".self::$account."&time=".$time."&sign=".md5($sign)."&scope=".$scope."&state=".urlencode($redirect_uri);
        header("location:".$url);
        exit;
    }


//获取全局access_token
     static function globeAccessToken(){
        $time = time();
        $sign = "access".self::$account.self::$passwd.$time."token";
        $url = ACCESS_TOKEN."/?acc=".self::$account."&time=".$time."&sign=".md5($sign);
        $data = self::http($url);
        if($data){
            $globeAccessToken = json_decode($data)->access_token;
            return $globeAccessToken;
        }
    }

//http请求函数
     static function http($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error){
            echo $error;
            return false;
        }
        return $data;
    }


}