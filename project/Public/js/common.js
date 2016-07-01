$indexUrl='../Action/index.php';
$index2Url='../Action/index2.php';
$shareUrl='../Action/share.php';

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
//页面调试
$pDebug={'ale':'false', 'con':'true'};
function pAlert(data){
    if($pDebug.ale=='true'){
        alert("pAlert[   "+data+"   ]");
    }
    if($pDebug.con=='true'){
        console.log("pConsole[   "+data+"   ]");
    }
}

//json text to json
function jsonGet(data){
    var $jsonObj=eval("("+data+")");
    return $jsonObj;
}

//静态页获取序列值[?]
function request(paras){
    var url = location.href;
    var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
    var paraObj = {};
    for (i=0; j=paraString[i]; i++){
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if(typeof(returnValue)=="undefined"){
        return "";
    }else{
        return returnValue;
    }
}

//静态页获取序列值[#]
function htmReq(paras){
    var url = location.href;
    var paraString = url.substring(url.indexOf("#")+1,url.length).split("&");
    var paraObj = {};
    for (i=0; j=paraString[i]; i++){
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if(typeof(returnValue)=="undefined"){
        return "";
    }else{
        return returnValue;
    }
}

//去掉所有html标记
function delHtmlTag(str){
    return str.replace(/<[^>]+>/g,"");//去掉所有的html标记
}

//随机值
function random(n, m){
    return Math.floor(Math.random()*(m-n+1)+n);
}


//验证集合
$name_Reg=/^[\u4e00-\u9fa5|a-zA-Z]*$/;
$mobile_Reg =/^1[3|4|5|7|8]\d{9}$/;
$ctMobile_Reg = /^(133|153|177|180|181|189)\d{8}$/;
$vCode_Reg = /^[0-9]{6}$/;

$phone_Reg=/^(020)?[0-9]{7,8}$/;

$account_Reg=/^(020)?[0-9]{7,8}$/;

$city_Reg=/^[\u4e00-\u9fa5|a-zA-Z]*$/;
$sex_Reg=/^(男|女)$/;


$inputReg={
    "nameReg":function(data){
        if(data==''||$name_Reg.test(data)===false){
            alert("请输入中文或英文姓名！");
            return false;
        }
    },
    "mobileReg":function(data){
        if(data==''||$mobile_Reg.test(data)===false){
            alert("请输入手机号码！");
            return false;
        }
    },
    "ctMobileReg":function(data){
        if(data==''||$ctMobile_Reg.test(data)===false){
            alert("请输入广州电信手机号码！");
            return false;
        }
    },
    "vCodeReg":function(data){
        if(data==''||$vCode_Reg.test(data)===false){
            alert("请输入六位数验证码！");
            return false;
        }
    },
    "phoneReg":function(data){
        if(data==''||$phone_Reg.test(data)===false){
            alert("请输入广州本地固话号码！");
            return false;
        }
    },
    "accountReg":function(data){
        if(data==''){
            alert("请输入广州本地宽带号码！");
            return false;
        }
    }


};