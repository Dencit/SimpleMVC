# SimpleMVC
"PHP-SimpleMVC-SMVC“ : 微信应用 轻量级MVC框架
#框架特点
1、前端完全异步，用一个简单的ajax封装，所有返回数据都在jsonAct.js里处理，全页面通用，正考虑写js模板引擎，改造成mmv结构。
2、后端把业务逻辑和查询分离，放在Action和Model两个目录中，数据操作采用wpdb为基类，对比较原始的查询方法封装多一层，暂不支持多表查询。
3、Admin目录是简单的管理后台，采用boostrap为UI，php
