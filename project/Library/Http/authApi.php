<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/6/2 Time: 11:21 **/
class authApi{

    private static $account = "heyi";
    private static $passwd = "WE2d9QWyr8Bd9TY";

    public function __construct(){

    }

//有限授权
    public static function usrAuth($redirect_uri = '',$scope = 'snsapi_userinfo'){
        $time = time();
        $sign = "OAuth2".self::$account.self::$passwd.$time;
        $url=OAUTH2_URI."/?acc=".self::$account."&time=".$time."&sign=".md5($sign)."&scope=".$scope."&state=".urlencode($redirect_uri);
        header("location:".$url);
        exit;
    }


//获取全局access_token
    public static function globeAccessToken(){
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
    public static function http($url){
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