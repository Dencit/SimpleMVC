<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/20 Time: 10:04 **/

require_once('../Model/shareModel.php');//加载查询模型

$state=isset($_POST['state'])?$_POST['state']:'';
$page=isset($_POST['page'])?$_POST['page']:'';

if($page=='share'&&$state=='shareState'){

    $jsonPost['shareReturn']='shareStatuOk';
    $jsonPost['uid']=$sid_get;
    $jsonPost['nick']=strip_tags($nickname_s);
    $jsonPost['head']=$head_s;
    $jsonPost['mobile']=$mobile_s;
    tool::jsonExit($jsonPost);
}

