<?php

namespace Apis;

class weiApi{


    protected static $APP_ID;
    protected static $APP_SECRET;

    function __construct($WEIXIN_CO){

        self::$APP_ID=$WEIXIN_CO->APP_ID;
        self::$APP_SECRET=$WEIXIN_CO->APP_SECRET;
    }


    /*
     * 获取access_token,每日限额2000
     */
    public function access_token(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$APP_ID."&secret=".self::$APP_SECRET;
        $data = $this->http_get($url);
        return $data;
    }

    /*
     *获取jsapi_ticket,
     */
    public function jsapi_ticket($access_token = ""){
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
        $data = $this->http_get($url);
        return $data;
    }

    /*
     * 卡券ticket
     */
    public function api_ticket($access_token = ""){
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=wx_card";
        $data = $this->http_get($url);
        return $data;
    }

    /*
     * 网页授权获取code
     */
    public function get_code($scope = 'snsapi_base',$state = 'jinxin',$redirect_uri = ''){
        $uri = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".AppID."&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";
        header("location:$uri");
        exit;
    }

    /*
     * 网页授权获取 openid,access_token
     */
    public function OAuth2_access_token($code = ''){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".AppID."&secret=".AppSecret."&code=$code&grant_type=authorization_code";
        $data = $this->http_get($url);
        return $data;
    }

    private function http_get($url = ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $ret = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error){
            return false;
        }
        return $ret;
    }
}