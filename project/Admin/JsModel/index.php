<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/19 Time: 11:30 **/

require_once('./indexBase.php');
require_once(FUNC.'/probability.php');//概率工具组

$state=isset($_POST['state'])?$_POST['state']:'';
$page=isset($_POST['page'])?$_POST['page']:'';

if($page=='index'&&$state=='pbtGet'){

    $fileGet=probability::fileGetVal('../../Cache/probability.php');
    tool::jsonExit(array("indexReturn"=>'pbtGetOk',"pbt"=>$fileGet));

}


if($page=='index'&&$state=='pbtRefresh'){

    $postData=$_POST['postData'];
    $url=CACHE.'/probability.php';

    $fileGetKey=probability::fileGetKey($url);
    $dataMerge=probability::dataMerge($fileGetKey,$postData);
    $Arr2Str=probability::Arr2Str($dataMerge);
    $fileWrite=probability::fileWrite($url,$Arr2Str);

    //print_r( $fileRefreshStr );exit;
    //$str='2元现金红包:49,长隆家庭乐票:100,星巴克电子咖啡券:150,10元话费:200,院线通电影票:250,不中奖:251';

    if($fileWrite){
        $jsonPost['indexReturn']='pbtRefreshOk';
        $jsonPost['postData']=$Arr2Str;
        tool::jsonExit($jsonPost);
    }else{
        $jsonPost['indexReturn']='pbtRefreshFail';
        $jsonPost['postData']=$Arr2Str;
        tool::jsonExit($jsonPost);
    }


}


if($page=='index'&&$state=='giftCount'){
    $jsonPost['indexReturn']='giftCountOk';
    $jsonPost['count']=$count;
    $jsonPost['total']=$total;
    tool::jsonExit($jsonPost);
}


