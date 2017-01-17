<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/11/9  Time: 10:39 */

namespace Encrypts;


use Controlers\base;
use Controlers\content;

class Encrypt {

    protected static $rankRowData;//保存数列结果

    protected static $saltArr;//随机盐

    protected static $truePw;//默认加密密匙

    protected static function __init(){
        //初始化随机盐//重复调用 只执行一次
        if( self::$saltArr==null ){

            //自定 随机盐
            self::$saltArr=array(
                "BangJu2016",
                "BJ2016",
                "BANGJU2016",
                "bj2016",
                "bangju2016++",
                "bangju2016--",
                "BangJu2016-888",
                "BJ2016-888",
                "BANGJU2016-888",
                "bj2016-888"
            );

            //自定 默认加密密匙
            self::$truePw='[PASS_WORD]';

        }

    }

    static function randSalt($len,$saltBase=null,$second=-1,$array=null){
        $second++;
        if($second<$len){
            $array[$second]=self::randStr(16,$saltBase);
            return self::randSalt($len,$saltBase,$second,$array);
        }
        else{
            return $array;
        }
    }

    static function randStr($len=null,$chars=null){
        if(empty($len)){$len=16;};
        if(empty($chars)){ $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; }
        //随机字符串
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }




//// 根据字符 排出所有可能的 排列
    static function rankString($str=null,$sub=null){

        $arr=str_split($str);
        self::$rankRowData='';
        self::rankStringCallBack($arr,$sub);
        return self::$rankRowData;

    }

    protected static function rankStringCallBack($arr=null,$sub=null){

        $strLen=count($arr);

        for($i=0;$i<$strLen;$i++){

            $newArr=$arr;

            $item=$arr[$i];
            //var_dump($item);echo'<br/>';

            unset($newArr[$i]);
            sort($newArr);
            //var_dump($newArr);echo'<br/>';

            $newSub=$sub;

            $newSub.=$item;

            if(empty($newArr)){
                //print_r($newSub);echo'<br/>';
                //echo '<p>end</p>';

                self::$rankRowData[]=$newSub;
                //print_r(self::$rankRowData);//

            }
            else{
                //print_r($newSub);echo'<br/>'; //exit;//
                self::rankStringCallBack($newArr,$newSub);
            }

        }

    }
//\\ 根据字符 排出所有可能的 排列


    protected static function md5Loop($second,$pw){
        //根据$second 次数回调  MD5加密
        $second--;
        //var_dump( $second );//
        if($second>0){
            $pwMd5=md5($pw);
            //var_dump( $pwMd5 );//
            return self::md5Loop($second,$pwMd5);
        }else{
            //var_dump( $pw );//
            return $pw;
        }
    }

    static function pwCrypt($pw=null){
        self::__init();
        if( !empty($pw) ){ self::$truePw=$pw; };
        //密码加密
        $rnd=number_format( rand(0,9) );
        $pw=self::$truePw.self::$saltArr[$rnd];
        //return $string;
        return self::md5Loop(1000,$pw) ;
        //return true;
    }

    static function pwCompare($md5Word){
        self::__init();
        $md5Word=strval($md5Word);
        //密码比对
        foreach(self::$saltArr as $n=>$v){
            $pw=self::$truePw.$v;
            $pwMd5=self::md5Loop(1000,$pw);
            if($md5Word==$pwMd5){ return true;}
        }
        return false;
    }

    static function edCrypt($string,$operation,$key=''){
        //加解密函数
        $key=md5($key);
        $key_length=strlen($key);
        $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
        $string_length=strlen($string);
        $rndkey=$box=array();
        $result='';
        for($i=0;$i<=255;$i++){
            $rndkey[$i]=ord($key[$i%$key_length]);
            $box[$i]=$i;
        }
        for($j=$i=0;$i<256;$i++){
            $j=($j+$box[$i]+$rndkey[$i])%256;
            $tmp=$box[$i];
            $box[$i]=$box[$j];
            $box[$j]=$tmp;
        }
        for($a=$j=$i=0;$i<$string_length;$i++){
            $a=($a+1)%256;
            $j=($j+$box[$a])%256;
            $tmp=$box[$a];
            $box[$a]=$box[$j];
            $box[$j]=$tmp;
            $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
        }
        if($operation=='D'){
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
                return substr($result,8);
            }else{
                return'';
            }
        }else{
            return str_replace('=','',base64_encode($result));
        }
    }

} 