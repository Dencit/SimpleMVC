<?php
/* Created by PhpStorm. | User: 陈鸿扬[SOMA] | Date: 2016/5/5 | Time: 15:47 */
header("Content-type:text/html;charset=utf-8");

require_once('../../Common/app.php');


$giftCount= $db->get_results("select * from ".GIFT_COUNT." Order by gid asc");
if($giftCount){
    $count=tool::std2arr($giftCount,'count');
    $total=tool::std2arr($giftCount,'total');
}