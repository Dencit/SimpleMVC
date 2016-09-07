<?php
namespace Commons ;

class tool
{

    function __construct(){



    }

    static function get_ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = '';
        }
        preg_match("/[\d\.]{7,15}/", $ip, $ips);
        $ip = isset($ips[0]) ? $ips[0] : 'unknown';
        return $ip;
    }

    static function conStr($Str)
    {
        return mb_convert_encoding($Str,'UTF-8','auto');
    }

    static function jsonSet($arr=array()){
        $json_obj=json_encode($arr);
        return $json_obj;
    }

    static function jsonExit($arr=array()){
        $json_obj=json_encode($arr);
        exit($json_obj);
    }

    //处理 get_results 返回的数组中包含std对象的情况
    static function std2arr($giftCount,$count){
        $arr=array();
        foreach($giftCount as $k=>$v){
            $arr[]=$giftCount[$k]->$count;
        }
        return $arr;
    }

    //比较两个数组间 同值的情况 有多少次
    static function arrayContrast ($firstArr,$secondArr,$count='0'){
        foreach($firstArr as $k=>$v){
            if($v==$secondArr[$k]){
                $count+=1;
            }
        }
        return $count;
    }

    //过滤符号
    static function filter_mark($text){
        if(trim($text)=='')return '';
        $text=preg_replace("/[[:punct:]\s]/",' ',$text);
        $text=urlencode($text);
        $text=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/",' ',$text);
        $text=urldecode($text);
        return trim($text);
    }


    //
    static function writeLogText($data){

        $dataText='';

        if(is_array($data)){
            foreach($data as  $k=>$v){
                if(end($data)){
                    $dataText.=$k.':'.$v.',';
                }else{
                    $dataText.=$k.':'.$v;
                }
            }
        }else{
            $dataText=$data;
        }

        $filename ="writeLogText.text";
        $filePath = CACHE."/".$filename;
        $rs = @file_put_contents($filePath,$dataText);

        if($rs && @chmod($fpath,0260)){
            return true;
        }

    }

    static function clearLogText($data){

    }


}