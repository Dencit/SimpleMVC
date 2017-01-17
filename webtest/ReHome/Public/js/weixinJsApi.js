//jsWeixin api

var weixinJsApi=function(){

};
weixinJsApi.prototype={

    init:function(account){
        //console.log(account);//

        var THIS=this;
        THIS.ACCOUT=account;

        return this;
    },

    set:function(option){
        //console.log('set');console.log(option);//

        var THIS=this;

        THIS.OPTION=option;

        THIS.post(function(result){//success

            THIS.wxConfig(result);
            THIS.wxReady(result);
            THIS.wxError(result);

        });

    },

    post:function(success){
        var THIS=this;

        $.ajax({
            url:THIS.ACCOUT.jsApi,
            dataType:"json",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success:function(result){

                success(result);

            }
        });

    },


    wxConfig:function(result){
        var THIS=this;
        wx.config({
            debug: false,
            appId: THIS.ACCOUT.appid,
            timestamp: result.timestamp,
            nonceStr: result.noncestr,
            signature: result.signature,
            jsApiList: [
                "onMenuShareAppMessage","onMenuShareTimeline","onMenuShareWeibo","onMenuShareQQ","onMenuShareQZone",
                "startRecord", "stopRecord", "onVoiceRecordEnd",
                "playVoice", "pauseVoice", "stopVoice", "onVoicePlayEnd",
                "uploadVoice", "downloadVoice",
                "chooseImage", "uploadImage"
            ]
        });

        return this;
    },

    wxReady:function(){
        var THIS=this;

        wx.ready(function(){

            //分享朋友
            if(THIS.OPTION.toFriend!==undefined){
                THIS.wxShare(THIS.OPTION.toFriend,'toFriend');
            }
            //分享朋友圈
            if(THIS.OPTION.friendShare!==undefined){
                THIS.wxShare(THIS.OPTION.friendShare,'friendShare');
            }
            //分享QQ
            if(THIS.OPTION.qqShare!==undefined){
                THIS.wxShare(THIS.OPTION.qqShare,'qqShare');
            }
            //分享微博
            if(THIS.OPTION.weiboShare!==undefined){
                THIS.wxShare(THIS.OPTION.weiboShare,'weiboShare');
            }
            //分享QQ空间
            if(THIS.OPTION.zoneShare!==undefined){
                THIS.wxShare(THIS.OPTION.zoneShare,'zoneShare');
            }
            //分享信息全部统一
            if(THIS.OPTION.allShare!==undefined){
                THIS.wxShare(THIS.OPTION.allShare,'allShare');
            }


        });

    },

    wxError:function(result){

        wx.error(function(result){
            console.log(result);
        });

    },

    wxShare:function(shareData,allType){
        //console.log('wxShare');console.log(shareData);console.log(allType);//

        var data={
            title:shareData.title, // 分享标题
            desc: shareData.desc, // 分享描述
            link: shareData.link, // 分享链接
            imgUrl: shareData.imgUrl,// 分享小图
            success:shareData.success,
            cancel:shareData.cancel
        };
        switch(allType){
            case 'toFriend': wx.onMenuShareAppMessage(data); break;
            case 'friendShare': wx.onMenuShareTimeline(data); break;
            case 'qqShare': wx.onMenuShareQQ(data);  break;
            case 'weiboShare': wx.onMenuShareWeibo(data); break;
            case 'zoneShare': wx.onMenuShareQZone(data); break;
            case 'allShare':
                wx.onMenuShareAppMessage(data);
                wx.onMenuShareTimeline(data);
                wx.onMenuShareQQ(data);
                wx.onMenuShareWeibo(data);
                wx.onMenuShareQZone(data);
                break;
            default:
                wx.onMenuShareAppMessage(data);
                wx.onMenuShareTimeline(data);
                wx.onMenuShareQQ(data);
                wx.onMenuShareWeibo(data);
                wx.onMenuShareQZone(data);
                break;
        }
    },

////副方法
    local:function(type,encode){
        var local='';
        switch(type){
            case 'host':
                local = window.location.host.split('#')[0];
                break;
            case 'url':
                local = window.location.href.split('#')[0];
                break;
        }
        if(encode!=undefined){
            local=encodeURIComponent(local);
        }
        //console.log(local);//
        return local;
    },

    //默认未参与游戏的 自定义方法
    noPlayShare:function(data,func){
        var THIS=this;

        option.allShare.title='未参与标题修改';// 分享标题
        option.allShare.desc='未参与描述修改';// 分享描述
        option.allShare.link= THIS.local('url');// 分享链接
        option.allShare.imgUrl='http://wx.bangju.com/ReHome/Public/images/icon.jpg';// 分享小图
        option.allShare.success=function(){};
        option.allShare.cancel=function(){};
        THIS.set(option);

        console.log(option.allShare);

        if(func!=undefined){ func(); }

    },

    //默认已参与游戏的 自定义方法
    playedShare:function(data,func){
        var THIS=this;

        option.allShare.title=data.fNick+"的已参与标题修改";
        option.allShare.desc=data.fNick+"的已参与描述修改";
        option.allShare.link= THIS.local('host')+"ReHome/?/weixin/index/sid-"+data.sid+"/";// 分享链接
        option.allShare.imgUrl='http://wx.bangju.com/ReHome/Public/images/icon.jpg';// 分享小图
        option.allShare.success=function(){};
        option.allShare.cancel=function(){};
        THIS.set(option);

        console.log(option.allShare);

        if(func!=undefined){ func(); }

    }


};

var wxja=new weixinJsApi();

var account={};
account.appid='wxa544f4ffe0ce6025';
account.jsApi='http://api.bangju.com/weixin/jsApi.php?'+"url="+wxja.local('url',1)+"&t="+new Date().getTime();
wxja.init(account);

var option={};
option.allShare={};



