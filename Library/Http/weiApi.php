<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/6/2 Time: 11:21 **/
class weiApi extends authApi
{
    public function __construct(){

    }

    //获取用户详细信息
    public static function usrInfo($openid = '',$access_token = ''){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = self::http($url);
        if ($data){
            $data = json_decode($data);
        }
        return $data;
    }

    //用户在关注了公众号之后获取其nickname、headimgurl等信息
    public static function subscribe($openid = '',$globeAccessToken=""){
        //return $globl_access_token;
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$globeAccessToken."&openid=".$openid."&lang=zh_CN";
        $data = self::http($url);
        if($data){
            $data = json_decode($data);
        }
        return $data;
    }

    //获限图片文件并保存在服务器
    public static function getMedia($media_id = "",$openid = "",$globeAccessToken=""){
        if(!$openid){ exit('!$openid'); }
        if(!$media_id){ exit('!$media_id'); }
        if(!$globeAccessToken ){ exit('!$globeAccessToken'); }

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$globeAccessToken&media_id=$media_id";
        $data = self::http($url);

        //exit($data);

        if($data){

            if(json_decode($data)){

                exit('fail');

            }else{

                $filename = $openid.'_'.date("YmdHis",TIME).".jpg";
                $fpath = PUBLIC_FILE."/Photos/".$filename;
                $rs = @file_put_contents($fpath,$data);

                if($rs && @chmod($fpath,0660)){
                    return ($filename);
                }

            }
        }
        return $data;
    }



}