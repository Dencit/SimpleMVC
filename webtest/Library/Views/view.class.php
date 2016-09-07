<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 2016/3/23 Time: 2:18*/

namespace Views;
use Debugs\frameDebug as FD;

class view{

    private static $view;

    function __construct(){
    }

    private static function msg($type){
        switch ($type){
            case 'from':
                return "FROM::Library/Views/view.class::";
                break;

        }
    }

    private static function htmlFiltrationRep($str){
        $str = trim($str);
        $str=preg_replace("{\t}","<_t_>",$str);
        $str=preg_replace("{\r\n}","<_r_n_>",$str);
        $str=preg_replace("{\r}","<_r_>",$str);
        $str=preg_replace("{\n}","<_n_>",$str);
        $str=preg_replace("/>\s*/",">",$str);
        $str=preg_replace("/\s*</","<",$str);
        return $str;
    }

    private static function htmlFiltrationBack($str){
        $str = trim($str);
        $str=preg_replace("{<_t_>}","\t",$str);
        $str=preg_replace("{<_r_n_>}","\r\n",$str);
        $str=preg_replace("{<_r_>}","\r",$str);
        $str=preg_replace("{<_n_>}","\n",$str);
        return $str;
    }

    private static function htmlFiltrationZip($str){
        $str = trim($str);
        $str=preg_replace("/\n+/","\n",$str);
        $str=preg_replace("/>\n/",">",$str);
        $str=preg_replace("/\n</","<",$str);
        $str=preg_replace("/>\s*/",">",$str);
        $str=preg_replace("/\s*</","<",$str);
        return $str;
    }


    static function tamplate($type){//加载 include 嵌套标签指向文件 到当前模板

        $url="Tamplate/".$type.".html";

        //
        if(!file_exists($url)){
            FD::frameDebugExit(self::msg('from').' tamplate():: 找不到 '.$url.' 主模版文件');
        }
        //

        $file=file_get_contents("$url","1");

        $match_patterns="/{{include::\"([^>]+?)\"}}/";
        preg_match_all($match_patterns, $file ,$include_path_group);
        //var_dump($include_path_group);exit;//

        foreach($include_path_group[1] as $k=>$v){
            $include_path_arr[]=$v;
        }
        //var_dump($include_path_arr);exit;//

        foreach($include_path_arr as $k=>$v){
            $patterns[]="/{{[include::]+\"($v)\"}}/i";

            $path="Tamplate/".$v.".html";
            $path_file=file_get_contents("$path","1");

            //print_r($path_file);exit;
            $replace[]="$path_file";
        }

        $filestr=preg_replace($patterns,$replace,$file);
        //var_dump($filestr);exit;//

        self::$view=$filestr;

    }

    static function asChange($sign,$type){//实时替换 可变模板 标记
        $url="Tamplate/".$type.".html";

        //
        if(!file_exists($url)){
            FD::frameDebugExit(self::msg('from').' asChange():: 找不到 '.$url.' 模版文件');
        }
        //

        $file=file_get_contents("$url","1");

        $patterns="/{{[change::]+\"($sign)\"}}/i";
        $replace="$file";

        $filestr=preg_replace($patterns,$replace,self::$view);
        //var_dump($filestr);exit;//

        self::$view=$filestr;
    }


    static function asChangeArr($arr=array()){//实时替换 可变模板 标记//数组方式

        foreach($arr as $k=>$v){
            $asArray[$k]=$v;
        }

        if($asArray!='' && self::$view!='') {

            foreach($asArray as $k=>$v){
                $sign=$k;
                $type=$v;

                $url = "Tamplate/" . $type . ".html";

                //
                if(!file_exists($url)){
                    FD::frameDebugExit(self::msg('from').' asChangeArr():: 找不到 '.$url.' 模版文件');
                }
                //

                $file = file_get_contents("$url", "1");

                $patterns[] = "/{{[change::]+\"($sign)\"}}/i";
                $replace[] = "$file";
            }

            $filestr = preg_replace($patterns, $replace, self::$view);
            //var_dump($filestr);exit;//
            self::$view = $filestr;

        }
    }


    static function forList($sign,$arr=array()){

        $listView=self::htmlFiltrationRep(self::$view);//过滤html换号符//空格精简//方便正则匹配
        //$listView=self::$view;

        //var_dump( $listView );//exit;//

        //获取循环模板中的html内容
        $match_patterns="/{{[forList::]+\"$sign\"}}(.*?|\W+|\w+){{\/+[forList::]+\"$sign\"}}/i";
        preg_match_all($match_patterns,$listView,$sign_group);

        //
        if(empty($sign_group[1])){
            FD::frameDebugExit(self::msg('from').' {{forList}}标签无匹配');
        }
        //

        $match_group=$sign_group[1];

        //var_dump($match_group);//exit;//

        $stringList='';
        foreach($arr as $k=>$v){
            unset($patterns);
            unset($replace);
            foreach($v as $m=>$n){
                $patterns[]="/\[\[($m)\]\]/";
                $replace[]=$n;
            }
        //var_dump($patterns);//
            $stringList.=preg_replace($patterns,$replace,$match_group)[0];
        }
        //var_dump($stringList);//exit;//

        $fileStr = preg_replace($match_patterns,$stringList, $listView);

        $fileStr=self::htmlFiltrationBack($fileStr);

        //print_r( $fileStr );exit;//

        self::$view =$fileStr;

    }


    static function asSign($sign,$value){//变量填充 当前模板中 单个标记
        $assign[$sign]=$value;
        //var_dump(self::$view);exit;

        if($assign!=''&& self::$view!=''){

            foreach($assign as $k=>$v){
                $patterns[]="/{{\s+($k)\s+}}|{{($k)}}/i";
                $replace[]="$v";
            }

        }else{
            exit('asSign fail!');
        }

        $filestr=preg_replace($patterns,$replace,self::$view);
        //var_dump($filestr);exit;//
        self::$view=$filestr;
    }

    static function asSignArr($arr=array()){//变量填充 当前模板中 单个标记//数组方式

        foreach($arr as $k=>$v){
            $asArray[$k]=$v;
        }

        if($asArray!='' && self::$view!=''){

            foreach($asArray as $k=>$v){
                $patterns[]="/{{\s+($k)\s+}}|{{($k)}}/";
                $replace[]="$v";
            }

        }

        $filestr=preg_replace($patterns,$replace,self::$view);
        //var_dump($filestr);exit;//
        self::$view=$filestr;

    }



    static function show(){//输出视图

        $fileStr=self::htmlFiltrationZip(self::$view);
        echo $fileStr;

    }


}


?>