<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/10/20  Time: 15:57 */
namespace Commons ;

class inputCheck{

    //匹配身份证
    static function check_identity($id='')
    {
        $set = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        $ver = array('1','0','x','9','8','7','6','5','4','3','2');

        $arr = str_split($id);
        if( count($arr)<17 ){ return false; }

        $sum = 0;
        for ($i = 0; $i < 17; $i++)
        {
            if (!is_numeric($arr[$i]))
            {
                return false;
            }
            $sum += $arr[$i] * $set[$i];
        }
        $mod = $sum % 11;
        if (strcasecmp($ver[$mod],$arr[17]) != 0)
        {
            return false;
        }
        return true;
    }


    static function check($subject=null,$type=null){

        $pattern='';

        switch($type){
            case 'name': $pattern = '/[\w\s+]|[\w\s]+/'; break;//匹配姓名
            case 'phone': $pattern = '/\d+\-\d+/'; break;//匹配电话
            case 'mail': $pattern = '/\w+@\w+\.\w+$/'; break;//匹配邮箱
        }

        preg_match($pattern, $subject, $match);
        if( !empty($match[0]) ){
            return true;
        }
        return false;

    }


    static function matchHtml($subject=null,$tag=null,$num=null){

        $pattern ='/\<'.$tag.'\>(.*?)\<\/'.$tag.'>/i';//匹配标签范围
        if( !empty($num) ){
            $pattern ='/<[\s]?'.$tag.'[\s]?(.*?)[\s]?\/[\s]?>/i';//匹配标签范围

            preg_match_all($pattern, $subject, $match);
            array_shift($match);
            foreach($match[0] as $n=>$v){

                echo $v;
                preg_match_all('/([\w]+)\=[\'|"|\s+|.??](.*?)[\'|"|\s+].*?/i',$v, $mat);
                array_shift($mat);
                $mat=array_combine($mat[0],$mat[1]);
                $match[0][$n]=$mat;

            }

        }else{
            preg_match_all($pattern, $subject, $match);
            array_shift($match);
        }

        if( !empty($match[0]) ){
            return $match[0];
        }
        return false;

    }

}