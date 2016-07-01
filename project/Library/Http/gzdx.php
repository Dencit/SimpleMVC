<?php
class gzdx{
    //跳转
    public static function head($redirect_uri = '',$scope = 'snsapi_userinfo'){
        $account = "heyi";
        $passwd = "WE2d9QWyr8Bd9TY";
        $time = time();
        $sign = "OAuth2".$account.$passwd.$time;
        header("location:http://lldfs.api.189go.cn/OAuth2/?acc=".$account."&time=".$time."&sign=".md5($sign)."&scope=snsapi_userinfo&state=".urlencode($redirect_uri));
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


/*    public static function qrcode($cardid){
        $client_id = "shell151198";
        $client_secret = "f948aab3-fc27-4134-b036-48dddf715bf6";

        $client_sign = md5("client_id=$client_id&client_secret=$client_secret&stimestamp=".TIME);
        $url = "http://wxmkt.gdshellcard.com/shell/frontend/apicreatecard/cardshell.php?client_sign=$client_sign&client_id=$client_id&stimestamp=".TIME."&cardid=$cardid";
        $data = self::http($url);
        if(!$data){
            return false;
        }
        $data = json_decode($data);
        if($data->result != 1 || $data->msg != "ok"){
            return false;
        }
        $data = $data->data;
        if($data->qrcodeurl){
            return $data->qrcodeurl;
        }else{
            return false;
        }
    }*/


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