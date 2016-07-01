<?php
class cgb{
//有限授权
    public static function head($redirect_uri = '',$scope = 'snsapi_userinfo'){
        $account = "heyi";
        $passwd = "WE2d9QWyr8Bd9TY";
        $time = time();
        $sign = "OAuth2".$account.$passwd.$time;
        $url="http://gf.api.189go.cn/OAuth2/?acc=".$account."&time=".$time."&sign=".md5($sign)."&scope=".$scope."&state=".urlencode($redirect_uri);
        header("location:".$url);
        exit;
    }
//获取用户详细信息
    public static function get($openid = '',$access_token = ''){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = self::http($url);
        if ($data){
            $data = json_decode($data);
        }
        return $data;
    }


//全局授权
   /* public static function headAll($redirect_uri = '',$scope = 'snsapi_base'){
        $account = "heyi";
        $passwd = "WE2d9QWyr8Bd9TY";
        $time = time();
        $sign = "access".$account.$passwd.$time."token";
        $url = "http://gf.api.189go.cn/access_token/?acc=$account&time=$time&sign=".md5($sign);
        header("location:".$url);
        exit;
    }*/
//获取用户详细信息
   /* public static function getAll($openid = '',$access_token = ''){
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = self::http($url);
        if ($data){
            $data = json_decode($data);
        }
        return $data;
    }*/
//


//用户在关注了公众号之后获取其nickname、headimgurl等信息
    public static function subscribe($openid = ''){
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = self::http($url);
        if($data){
            $data = json_decode($data);
        }
        return $data;
    }

    public static function get_access_token(){
        $account = "heyi";
        $passwd = "WE2d9QWyr8Bd9TY";
        $time = time();
        $sign = "access".$account.$passwd.$time."token";
        $url = "http://gf.api.189go.cn/access_token/?acc=$account&time=$time&sign=".md5($sign);
        $data = self::http($url);
        if ($data){
            $access_token = json_decode($data)->access_token;
        }
        return $access_token;
    }


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

//////

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