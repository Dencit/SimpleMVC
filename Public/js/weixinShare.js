//jsWeixin api
var url = location.href.split('#')[0];
url = encodeURIComponent(url);
var appid = "wxdcf8b6c546750b08";//mld
var ajaxLink= "http://mld.api.189go.cn/mld.php";

var allLink="http://webtest.189go.cn/testProj";
var allImgLink="http://webtest.189go.cn/testProj";

//在这里定义微信分享内容
var __noPlayData = {
    title: "未参与标题", // 分享标题
    desc: "未参与描述", // 分享描述
    link: allLink, //分享链接
    imgUrl: allImgLink+'/Public/images/icon.jpg',//分享小图
    success:function () {
        window.location.href='../';
    }
};

var __shareData = {
    title: "已参与标题", // 分享标题
    desc: "已参与描述", // 分享描述
    link: allLink+'/share.php', //分享链接
    imgUrl: allImgLink+'/Public/images/icon.jpg',//分享小图
    success:function () {
        window.location.href='./index2.html';
    },
    cancel: function () {

    }
};

function initShare(data){
    //分享朋友
    wx.onMenuShareAppMessage({
        title:data.title, // 分享标题
        desc: data.desc, // 分享描述
        link: data.link, // 分享链接
        imgUrl: data.imgUrl,// 分享小图
        success:data.success,
        cancel:data.cancel
    });
    //分享朋友圈
    wx.onMenuShareTimeline({
        title:data.title, // 分享标题
        desc: data.desc, // 分享描述
        link: data.link, // 分享链接
        imgUrl: data.imgUrl,// 分享小图
        success:data.success,
        cancel:data.cancel
    });
}

$playState='noPlay';

function __jweixin(){

    $.ajax({
        url:ajaxLink+"?url="+url+"&t="+new Date().getTime(),
        dataType:"json",
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
        success:function(data){

            wx.config({
                debug: false,
                appId: appid,
                timestamp: data.timestamp,
                nonceStr: data.noncestr,
                signature: data.signature,
                jsApiList: [
                    "onMenuShareTimeline",
                    "onMenuShareAppMessage",
                    "startRecord",
                    "stopRecord",
                    "onVoiceRecordEnd",
                    "playVoice",
                    "pauseVoice",
                    "stopVoice",
                    "onVoicePlayEnd",
                    "uploadVoice",
                    "downloadVoice",
                    "chooseImage",
                    "uploadImage"
                ]
            });

            wx.ready(function(){

                switch($playState){
                    case 'noPlay':
                        initShare(__noPlayData);
                        break;
                    case 'sharePlay':
                        initShare(__shareData);
                        break;
                }

            });

            wx.error(function(res){
                cosole.log("error");
            });

        }
    });

}
