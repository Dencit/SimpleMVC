<?php
class gddx{

    public static function head($redirect_uri = '',$scope = 'snsapi_base'){
        $appid="wx39969b2a53bc47f8";
        $sign=md5("wx39969b2a53bc47f88yue!@*&");
        $redirect=urlencode("http://gd.189.cn/weixin/redirect.action?sysacc=outlet&authFlag=1&apptype=1&appId=".$appid."&sign=".$sign);

        $state=urlencode($redirect_uri);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";

        header("location:$url");
        exit;
    }


    public static function get($openid='',$access_token = ''){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = self::http($url);
        return $data;
    }

    private static function http($url){
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
        return json_decode($data);
    }



    //发送验证码
    public static function sms($mobile = "",$code = ""){
        $url = "http://www.mini189.cn/fans_interface/msg/sendCheckCode/$mobile/$code";
        $data = self::http($url);
        if($data == "success"){
            return true;
        }else{
            return false;
        }
    }

    //网龄查询
    public static function age($mobile = ""){
        $url = "http://www.mini189.cn/yqt_fans/interface/phoneNumber/$mobile";
        $data = self::http($url);
        return $data;
    }
    public static function age_other($acc = "",$passwd = ""){
        $url = "http://www.mini189.cn/yqt_fans/interface/adslDetails/$acc/$passwd";
        $data = self::http($url);
        return $data;
    }


}