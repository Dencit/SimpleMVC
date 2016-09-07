/* Created by User: soma Worker: 陈鸿扬 on 16/8/13 */

//incule: common.js

//函数调试
$iDebug={'ale':'false', 'con':'true'};
function iAlert(data){
    if($iDebug.ale=='true'){
        alert("iAlert[   "+data+"   ]");
    }
    if($iDebug.con=='true'){
        console.log("iConsole[   "+data+"   ]");
    }
}
//对象调试
$oDebug={'objCon':'true'};
function iObjCon(objRes){
    if($oDebug.objCon=='true'){
        console.log(objRes);
    }
}

//json text to json
function jsonGet(data){
    var $jsonObj=eval("("+data+")");
    return $jsonObj;
}

var ajax=function(){};
ajax.prototype={
    //start

    post:function(url,inputData,func,async){

        if(async==''){
            $.ajaxSetup({async : true});//默认异步型
        }else{
            $.ajaxSetup({async : false});//阻断型
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: inputData,
            dataType: 'html',
            cache:false,
            success: function (result) {

                iAlert("返回HTML数据::↓↓::postBackHtmlData");//正确时 输出html内容//错误时 直接输出后端报错信息
                iAlert(result);

                var res = jsonGet(result);//转为Object对象//json
                iAlert("返回JSON数据::↓↓::getBackJsonData");
                iObjCon(res);

                func(res);

            },
            error:function(msg){

                iAlert("getBackResult::↓↓");
                iObjCon(msg.toSource());//

            }
        });

    }

    //end
};

var ckUid=function(result){

    switch(result.checkUid){
        case 'noUid':
            alert("微信未授权！返回首页?");
            window.location.href='../?/weixin/index/';
            break;
    }


};

var ckSid=function(result){
    switch(result.checkSid){
        case 'noSid':
            alert("非法操作！将返回首页..");
            window.location.href='../?/weixin/index/';
            break;
    }
}

var ajax = new ajax();