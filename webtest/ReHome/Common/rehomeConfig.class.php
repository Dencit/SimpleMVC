<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/22  Time: 19:35 */

namespace Commons;

use stdClass as stdClass;

class rehomeConfig extends config {

    function __construct($data=null){


        ////配置二次修改

        $config=new stdClass();

        $config->DEBUG=new stdClass();
        $config->DEBUG='on';

        $config->DB=new stdClass();
        $config->DB->USER='root';
        $config->DB->PASSWD='bangju2015';
        $config->DB->NAME='webtest';
        $config->DB->HOST='127.0.0.1';
        $config->DB->DBMS='mysql';
        $config->DB->PREFIX='test_';
        $config->DB->DEBUG='on';

        $config->DB->TABLE=new stdClass();
        $PREFIX=$config->DB->PREFIX;
        $config->DB->TABLE->USR=$PREFIX.'users';
        $config->DB->TABLE->USR_INFO=$PREFIX.'users_info';
        $config->DB->TABLE->USR_GET=$PREFIX.'users_get';
        $config->DB->TABLE->USR_REC=$PREFIX.'users_rec';
        $config->DB->TABLE->GIFT_COUNT=$PREFIX.'giftcount';


        $config->DB->REDIS=new stdClass();
        $config->DB->REDIS->HOST='127.0.0.1';
        $config->DB->REDIS->PORT='6379';
        $config->DB->REDIS->AUTH='[PASS_WORD]';
        $config->DB->REDIS->PREFIX='test_';
        $config->DB->REDIS->TIMEOUT='600';//600:10分钟 //28800:8小时过期

        $config->PATH=new stdClass();
        $config->PATH->ACCOUNT='[NAME]';
        $config->PATH->PASSWD='[PASS_WORD]';
        $config->PATH->HTTP_BASE='http://wx.host.com';
        $config->PATH->API_URL='http://wx.host.com/Api/?/weixinco';
        $config->PATH->OAUTH2_URI='http://api.host.com/weixin/OAuth2';
        $config->PATH->ACCESS_TOKEN='http://api.host.com/weixin/access_token';


        //作者标记
        $config->AUTHOR=new stdClass();
        $config->AUTHOR->EMAIL='632112883@qq.com';//邮箱

        //认证号//服务号
        $config->WEIXIN=new stdClass();
        $config->WEIXIN->APP_ID='[APP_ID]';//AppID
        $config->WEIXIN->APP_SECRET='[APP_SECRET]';//AppSecret

        //企业号
        $config->WEIXIN_CO=new stdClass();
        $config->WEIXIN_CO->CORP_ID='[CORP_ID]';
        $config->WEIXIN_CO->CORP_SECRET='[CORP_SECRET]';
        //企业号 回调参数
        $config->WEIXIN_CO->TOKEN='[TOKEN]';
        $config->WEIXIN_CO->ENCODING_AESKEY='[ENCODING_AESKEY]';


        $config->FRAME=new stdClass();
        $config->FRAME->ROOT_PROJECT='ReHome';//为空则默认根目录//当前项目主目录 controler.class.php , model.class.php 调用
        $config->FRAME->ROOT_CONTROLER='Controler';//二级目录//控制器目录//controler.class.php 调用
        $config->FRAME->ROOT_MODELER='Modeler';//二级目录//数据模型目录//model.class.php 调用
        $config->FRAME->BASE_MODELER='WpBaseModel';//默认公用模型//wpBaseModel.class.php 调用
        $config->FRAME->URL_TYPE='Path_Info';//地址栏格式
        $config->FRAME->DEBUG='on';//框架调试开关

        $this->config=$config;

        //\\配置二次修改


    }

} 