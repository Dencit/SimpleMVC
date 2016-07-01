<?php
class mld{
//有限授权
    public static function head($redirect_uri = '',$scope = 'snsapi_userinfo'){
        $account = "heyi";
        $passwd = "WE2d9QWyr8Bd9TY";
        $time = time();
        $sign = "OAuth2".$account.$passwd.$time;
        $url=OAUTH2_URI."/?acc=".$account."&time=".$time."&sign=".md5($sign)."&scope=".$scope."&state=".urlencode($redirect_uri);
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

//用户在关注了公众号之后获取其nickname、headimgurl等信息
    public static function subscribe($openid = ''){
        $access_token_gl = self::get_access_token();
        //return $access_token_gl;
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token_gl."&openid=".$openid."&lang=zh_CN";
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
        $url = ACCESS_TOKEN."/?acc=".$account."&time=".$time."&sign=".md5($sign);
        $data = self::http($url);
        if($data){
            $access_token_gl = json_decode($data)->access_token;
            return $access_token_gl;
        }
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


}