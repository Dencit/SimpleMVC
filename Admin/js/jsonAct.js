//incule: common.js
//获取数据 同步
function __get (inputData,url){
    $.ajaxSetup({async : false});
    $.post(url,inputData,function(data){
        iAlert("getBackData :↓↓:  "+data);
        var res =jsonGet(data);//转为Object对象
        __getBack(res);
    });
}
//发送数据 异步
function __post(inputData,url){
    $.ajaxSetup({async :true});
    $.post(url,inputData,function(data){
        iAlert("postBackData :↓↓:  "+data);
        var res = jsonGet(data);//转为Object对象
        __postBack(res);//通过外置函数做状态处理
    });
}
//数据交换 同步
function __connect(inputData,url){
    $.ajaxSetup({async :true});
    $.post(url,inputData,function(data){
        iAlert("conBackData :↓↓:  "+data);
        var res = jsonGet(data);//转为Object对象
        __conBack(res);//通过外置函数做状态处理
    });
}
////////////////////////////////////////////////


//调数据用//再通过唯一的 __getBack(result) 处理页面数据
//$gdata={"state":'get'};
//__get($gdata,$queUrl);//显示

function  __getBack(result){
    //返回数据处理区域

    iAlert("getBackResult::↓↓");
    iObjCon(result);//

    switch(result.checkUid){
        case 'noUid':
            alert("微信未授权！返回首页?");
            window.location.href='../';
            break;
    }

    switch(result.indexReturn){
        case 'pbtGetOk':
            pAlert('pbtGetOk');
            $pbt=new Array();
            $pbt=result.pbt;
            iObjCon($pbt);
            $('.pbtInp').eq(0).val($pbt[0]);
            $('.pbtInp').eq(1).val($pbt[1]);
            $('.pbtInp').eq(2).val($pbt[2]);
            $('.pbtInp').eq(3).val($pbt[3]);
            $('.pbtInp').eq(4).val($pbt[4]);
            $('.pbtInp').eq(5).val($pbt[5]);
            break;
        case 'giftCountOk':
            pAlert('giftCountOk');

            $count=result.count;
            $total=result.total;
            iObjCon($count);
            iObjCon($total);

            eqChangeText('.count',$count,'4');
            eqChangeText('.total',$total,'4');

            break;
    }

    //

}


//提交数据用//再通过唯一的 __postBack(result) 根据返回关键字触发页面事件
//$pdata={"state":'post'};
//__post($pdata,$queUrl);//提交

function __postBack(result){

    iAlert("postBackResult::↓↓");
    iObjCon(result);//

    switch(result.checkUid){
        case 'noUid':
            alert("微信未授权！返回首页?");
            window.location.href='../';
            break;
    }

    //状态处理区域
    switch (result.indexReturn){
        case 'pbtRefreshOk':
            pAlert('pbtRefreshOk');
            alert('概率更新成功！');
            break;
        case 'pbtRefreshFail':
            pAlert('pbtRefreshFail');
            alert('概率更新失败!\n请检查概率文件读写权限.');
            location.replace(location.href);
            break;
    }

    //

}


//提交数据用//再通过唯一的 __conBack(result) 根据返回关键字触发页面事件
//$cdata={"state":'post'};
//__conBack($cdata,$queUrl);//提交

function __conBack(result){

    iAlert("postBackResult::↓↓");
    iObjCon(result);//

    switch(result.checkUid){
        case 'noUid':
            alert("微信未授权！返回首页?");
            window.location.href='../';
            break;
    }

    //状态处理区域
    switch (result.postMobile){

    }

    switch(result.postGift){

    }

    //

}







