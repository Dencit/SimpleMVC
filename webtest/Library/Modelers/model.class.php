<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/7/30  Time: 13:15 */

namespace Modelers;
use Debugs\frameDebug as FD;
use Commons\rootProj as rootProj;

//model装载类
class model {

    private static $from;

    private static $index;//模型名
    private static $rootProj;//项目夹，目标文件夹

    private static $FRAME;//框架目录节点设置 对象
    private static $DB;//数据库连接设置 对象

    //private static $model_root_url;//网站根目录开始 预先指定的 控制器 路径

    function __construct($FRAME=null,$DB=null){

        self::$FRAME=$FRAME;
        self::$DB=$DB;

        self::$rootProj=new rootProj($FRAME->ROOT_PROJECT,$FRAME->ROOT_MODELER);//【项目夹，目标文件夹】传入调用页 全局变量或变量

        return $this;
    }

    static function init($FRAME=null,$DB=null){

        self::$FRAME=$FRAME;
        self::$DB=$DB;

        self::$rootProj=new rootProj($FRAME->ROOT_PROJECT,$FRAME->ROOT_MODELER);//【项目夹，目标文件夹】传入调用页 全局变量或变量

        
    }

    static function model($index=null,$set=null){

        if(empty($index)){
            self::$index=self::$FRAME->BASE_MODELER;
            self::$rootProj=new rootProj('Library','Modelers');//【项目夹，目标文件夹】传入调用页 全局变量或变量
        }
        else{
            self::$index=$index;
            self::$rootProj=new rootProj(self::$FRAME->ROOT_PROJECT,self::$FRAME->ROOT_MODELER);//【项目夹，目标文件夹】传入调用页 全局变量或变量
        }

        //加载 index modeler 可同时传参给 构造函数
        if($set==''){
            return self::set(self::$DB);
        }else{
            return self::set($set);
        }

    }

    protected static function set($newParam='')//new classs时 传给构造函数的 初始化参数
    {

        $index=self::$index;

        $modelFolder=self::$rootProj->getFolder();//得到绝对路径
        //var_dump( $modelFolder );//

        $index_req_url=
            $modelFolder.DIRECTORY_SEPARATOR
            .$index.'.class.php';
        //var_dump($index_req_url);//

        require_once($index_req_url);
        $index_class=self::trimall('\\Modelers\\'.$index);
        //var_dump($index_class);//

        $index=new $index_class($newParam);

        return $index;

    }


    protected static function trimall($str)//删除空格
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        return str_replace($qian,$hou,$str);
    }

} 