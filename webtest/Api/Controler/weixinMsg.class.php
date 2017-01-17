<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/11/10  Time: 16:48 */

namespace Controlers;

use \Commons\tool as tool;//工具类


class weixinMsg extends apiBase {

    function __construct(){
        parent::__construct();//初始化 父类的构造函数

        include_once LIBRARY."/Thirds/weixinSdk/bizMsgCrypt/WXBizMsgCrypt.php";

        $sVerifyMsgSig = tool::is_Get('msg_signature'); $sVerifyTimeStamp = tool::is_Get('timestamp' );
        $sVerifyNonce = tool::is_Get( 'nonce' ); $sVerifyEchoStr = tool::is_Get( 'echostr' );
        //var_dump($sVerifyMsgSig);var_dump($sVerifyTimeStamp);
        //var_dump($sVerifyNonce);var_dump($sVerifyEchoStr);exit;//

        $wxcpt = new \WXBizMsgCrypt(self::$WX->TOKEN,self::$WX->ENCODING_AESKEY,self::$WX->CORP_ID);//企业号在公众平台上设置的参数
        $sEchoStr = "";
        $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);

        if ($errCode == 0) { echo $sEchoStr; } // 验证URL成功，将sEchoStr返回
        //else { print("ERR: " . $errCode . "\n\n"); }

    }

    function pushNews(){



    }

} 