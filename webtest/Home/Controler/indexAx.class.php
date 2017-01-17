<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/7/28  Time: 09:37 */

namespace Controlers;

use \Commons\tool as tool;//工具类
use \Commons\jump as jump;
use \Controlers\urlRoute as urlRoute;
use \Controlers\urlSerial as I;

use \Modelers\model as model;//model装载器
use \Https\weiApi as weiApi;

use Commons\probability as probability;//概率工具组


class indexAx extends homeBase {

    private static $uid;
    private static $MS;

    function __construct(){
        parent::__construct();//初始化 父类的构造函数

        //判断用户身份
        $glob_usr=$this->homeDescAx();
        self::$uid=$glob_usr->uid;

        self::$MS=model::model('indexAx');//装载对应model时，自动连接数据库

    }



    function testPost(){

        $result=json_encode($_POST);
        tool::jsonResult($result,'0','post ok !');

    }

    function testPostList(){

        $result=json_encode($_POST);
        tool::jsonResult($result,'0','post list ok !');

    }

    function testGet(){

        $data=new \stdClass();
        $data->list1='testGetA';
        $data->list2='testGetB';
        $data->list3='list four';
        $data->list5='testGetE';

        tool::jsonResult($data,'0','get ok !');
    }


    function testGetList(){

        $data=[];
        $data[0]=new \stdClass();
        $data[0]->list1='testGetList A1';
        $data[0]->list2='testGetList B1';
        $data[0]->list3='list four';
        $data[0]->list5='testGetList E1';
        $data[1]=new \stdClass();
        $data[1]->list1='testGetList A2';
        $data[1]->list2='testGetList B2';
        $data[1]->list3='list three';
        $data[1]->list5='testGetList E2';
        $data[2]=new \stdClass();
        $data[2]->list1='testGetList A3';
        $data[2]->list2='testGetList B3';
        $data[2]->list3='list four';
        $data[2]->list5='testGetList E3';

        tool::jsonResult($data,'0','get list ok !');
    }


    function usrState(){

        //$this->homeDesc()方法里 统一做身份验证，这里默认为通过。

        tool::jsonResult('','0');

    }

    function usrLottery(){

        $uid=self::$uid;

        //$probability=new probability();

        $MS=self::$MS;

        $index=$MS->index($uid);
        //var_dump($index);exit;//

            $rnd=rand(1,1000);
            $fileGet=probability::fileGetVal(CACHE.'/probability.php');
            $setInt=probability::setInterval($fileGet);
            //print_r($setInt);
            $item=probability::getSign($rnd,$setInt);
            //print_r($item);exit;

            if($index->gift!=0 ){
                $jsonPost['rnd']=$rnd;
                $jsonPost['interval']=$item['inv'];
                $jsonPost['item']=$item['s'];
                tool::jsonResult($jsonPost,'-1','haveGift');
            }

            $gift_count=$MS->get_row($MS->prepare("select * from ".self::$TB->GIFT_COUNT." WHERE gid=%s ",$item['s']));

            if($gift_count){
                $g_count=$gift_count->count;
            }else{
                exit('$gift_count bug');
            }

            if($g_count==0&&$item!='1'){

                $jsonPost['rnd']=$rnd;
                $jsonPost['interval']=$item['inv'];
                $jsonPost['item']=$item['s'];
                tool::jsonResult($jsonPost,'-1','endGift');
            }

            $jsonPost['rnd']=$rnd;
            $jsonPost['interval']=$item['inv'];
            $jsonPost['item']=$item['s'];
            tool::jsonResult($jsonPost,'-1','emptyGift');

    }


    function shareState(){

        //$uid=self::$uid;
        $sid=tool::is_Post('sid');
        //var_dump($sid);exit;//

        if($sid==''){ tool::jsonResult('','-1','noSid'); }

        $MS=self::$MS;
        $share=$MS->share($sid);

        $jsonPost['uid']=$share->uid;
        $jsonPost['nick']=strip_tags($share->nickname);
        $jsonPost['head']=$share->headimgurl;
        $jsonPost['mobile']=$share->mobile;
        tool::jsonResult($jsonPost,'0','shareStatuOk');


    }



} 