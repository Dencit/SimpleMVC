<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/11/29  Time: 14:30 */

namespace Modelers;


class wxpay extends apiBase
{

    function __construct($DB){
        parent::__construct($DB);
    }

    function accountRefl($uid,$balance_add,$subsidy_add,$time_recharge){

        $select="balance+".$balance_add.",subsidy+".$subsidy_add;

        $where['uid']=$uid;
        $balanceSum=$this->rowSelectMath(self::$TB->ACCOUNT,$select,$where);
        $balanceSum->time_recharge=$time_recharge;
        $balanceAdd=$this->rowUpdate(self::$TB->ACCOUNT,$balanceSum,$where);
        if(!$balanceAdd){return 'balanceAdd had!';}
        else{return true;}

    }



}