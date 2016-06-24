<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/19 Time: 11:30 **/

require_once('../Model/indexModel.php');//加载查询模型

$state=isset($_POST['state'])?$_POST['state']:'';
$page=isset($_POST['page'])?$_POST['page']:'';

if($page=='index'&&$state=='usrState'){

    if($recEd !='0'){
        $jsonPost['indexReturn']='reced';
        $jsonPost['reced']='1';
        $jsonPost['uid']=$uid_get;
        $jsonPost['nick']=$nickname;
        $jsonPost['head']=$head;
        tool::jsonExit($jsonPost);
    }

    $jsonPost['indexReturn']='ok';
    $jsonPost['reced']='0';
    $jsonPost['uid']=$uid_get;
    $jsonPost['nick']=$nickname;
    $jsonPost['head']=$head;
    tool::jsonExit($jsonPost);
}



