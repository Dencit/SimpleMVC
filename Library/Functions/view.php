<?php
/**
 * Created by PhpStorm.
 * User: SOMA
 * Date: 2016/3/23
 * Time: 2:18
 */

class view{

    private static $assign;
    private static $aslist;

    public function __construct(){
    }

    public static function assign($sign,$value){
        self::$assign[$sign]=$value;
    }

    public static function fotlist($arr=array()){
        foreach($arr as $k=>$v){
            self::$aslist[$k]=$v;
        }
    }

    public static function display($url){
        $file=file_get_contents("$url","1");
        foreach(self::$assign as $k=>$v){
            $patterns[]="/\{\%\s+(::$k)\s+\%\}/";
            $replace[]="$v";
        }
        $filestr=preg_replace($patterns,$replace,$file);
        echo $filestr;
    }
}


?>