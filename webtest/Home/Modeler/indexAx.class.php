<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/7/30  Time: 12:01 */

namespace Modelers;

use \Commons\homeConfig as con;

class indexAx extends homeBase {

    function __construct($DB){
        parent::__construct($DB);
    }


    function index($uid_get){

        $whereArray['uid']=$uid_get;

        $select='uid,nickname,headimgurl,sex';
        $USR=$this->rowSelect(self::$TB->USR,$select,$whereArray);
        if(!$USR){ exit('$USR fail!'); }

        $select='reced';
        $USR_REC=$this->rowSelect(self::$TB->USR_REC,$select,$whereArray,'reced desc');
        if(!$USR_REC){ exit('$USR_REC fail!'); }

        $select='gift';
        $USR_GET=$this->rowSelect(self::$TB->USR_GET,$select,$whereArray,'time desc');
        //var_dump($USR_GET);exit;//
        if(!$USR_GET){ exit('$USR_GET fail!'); }

        $select='mobile';
        $USR_INFO=$this->rowSelect(self::$TB->USR_INFO,$select,$whereArray);
        if(!$USR_INFO){ exit('$USR_INFO fail!'); }

        $data=new \stdClass();
        $data->uid=$USR->uid;
        $data->nickname=$USR->nickname;
        $data->headimgurl=$USR->headimgurl;
        $data->sex=$USR->sex;
        $data->reced=$USR_REC->reced;
        $data->gift=$USR_GET->gift;
        $data->mobile=$USR_INFO->mobile;

        //var_dump($data);exit;

        return $data;
    }


    function share($sid_get){

        $whereArray['uid']=$sid_get;

        $selesct='uid,nickname,headimgurl';

        $USR=$this->rowSelect(self::$TB->USR,$selesct,$whereArray);
        if(!$USR){ exit('$USR fail!'); }

        $selesct='mobile';
        $USR_INFO=$this->rowSelect(self::$TB->USR_INFO,$selesct,$whereArray);
        if(!$USR_INFO){ exit('$USR fail!'); }

        $data=new \stdClass();
        $data->uid=$USR->uid;
        $data->nickname=$USR->nickname;
        $data->headimgurl=$USR->headimgurl;
        $data->mobile=$USR_INFO->mobile;

        return $data;
    }


} 