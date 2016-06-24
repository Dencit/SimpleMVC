<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/19 Time: 11:30 **/

require_once('../Model/index2Model.php');//加载查询模型

$state=isset($_POST['state'])?$_POST['state']:'';
$page=isset($_POST['page'])?$_POST['page']:'';

if($page=='index2'&&$state=='usrState'){

        if($recEd !='0'){
        $jsonPost['index2Return']='reced';
        $jsonPost['reced']='1';
        $jsonPost['uid']=$uid_get;
        $jsonPost['nick']=$nickname;
        $jsonPost['head']=$head;
        tool::jsonExit($jsonPost);
    }

    $jsonPost['index2Return']='ok';
    $jsonPost['reced']='0';
    $jsonPost['uid']=$uid_get;
    $jsonPost['nick']=$nickname;
    $jsonPost['head']=$head;
    tool::jsonExit($jsonPost);
}


require_once(FUNC.'/probability.php');//概率工具组

if($page=='index2'&&$state=='usrLottery'){

    $rnd=rand(1,1000);
    $fileGet=probability::fileGetVal('../Cache/probability.php');
    $setInt=probability::setInterval($fileGet);
    //print_r($setInt);
    $item=probability::getSign($rnd,$setInt);
    //print_r($item);exit;


    if($gift!=0 ){
        $jsonPost['index2Return']='haveGift';
        $jsonPost['rnd']=$rnd;
        $jsonPost['interval']=$item['inv'];
        $jsonPost['item']=$item['s'];
        tool::jsonExit($jsonPost);
    }


    $gift_count=$db->get_row($db->prepare("select * from ".GIFT_COUNT." WHERE gid=%s ",$item['s']));
    if($gift_count){
        $g_count=$gift_count->count;
    }else{
        exit('$gift_count bug');
    }



    if($g_count==0&&$item!='1'){

        $jsonPost['index2Return']='endGift';
        $jsonPost['rnd']=$rnd;
        $jsonPost['interval']=$item['inv'];
        $jsonPost['item']=$item['s'];
        tool::jsonExit($jsonPost);
    }

    $jsonPost['index2Return']='emptyGift';
    $jsonPost['rnd']=$rnd;
    $jsonPost['interval']=$item['inv'];
    $jsonPost['item']=$item['s'];
    tool::jsonExit($jsonPost);
}


