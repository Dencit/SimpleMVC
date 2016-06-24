<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/17 Time: 15:04 **/


function xslTamp($data,$tableName,$view=''){

if($view!='view'){
    header("Content-Type:application/vnd.ms-excel");
    header("Content-type: text/plain");
    header("Content-Disposition:attachment;filename=".$tableName.".xls");
}

echo"
<html>
    <head>
        <title>ADMIN</title>
        <meta charset='utf-8'>
    </head>
    <body>

<style>
table,tr,td{border-collapse: collapse; border: 1px solid #ccc; text-align: center; min-width: 80px;padding: 8px;}
</style>

<table>
    <thead>
        <tr><th id='uid'>用户id</th><th id='openid'>OPENID</th><th id='nick'>用户昵称</th><th id='logtime'>参与时间</th><th id='gift'>奖品</th><th id='mobile'>手机</th></tr>
    </thead>
    <tbody>
";
    foreach($data as $k=>$v){
        $uid=$data[$k]->uid;
        $nick=$data[$k]->nick;
        $gift=$data[$k]->gift;
        switch($gift){
            case 0 :$gift="2元现金红包";break;
            case 2 :$gift="长隆家庭乐票";break;
            case 3 :$gift="星巴克电子咖啡券";break;
            case 4 :$gift="10元话费";break;
            case 5 :$gift="院线通电影票";break;
        }
        $time=$data[$k]->time;//$users_get

        if(isset($data[$k]->mobile)){$mobile=$data[$k]->mobile;}
        else{ $mobile='-'; }

        if(isset($data[$k]->openid)){$openid=$data[$k]->openid;}
        else{ $openid='—'; }

        $tdStr='<tr><td>'.$uid.'</td><td>'.$openid.'</td><td>'.$nick.'</td><td>'.date('Y-m-d h:s:i',$time).'</td><td>'.$gift.'</td><td>'.$mobile.'</td></tr>';
        echo $tdStr;

    }


echo "
    </tbody>
</table>

    </body>
</html>
";


}