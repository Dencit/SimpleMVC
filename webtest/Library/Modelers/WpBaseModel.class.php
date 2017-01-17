<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/6/18  Time: 20:16 */

namespace Modelers;

use Debugs\testTool as tsTool;

class WpBaseModel extends WpDb {

    protected static $debug;
    protected static $cut;

    function __construct($DB){
        parent::__construct($DB->USER,$DB->PASSWD,$DB->NAME,$DB->HOST);

    }


    function debug(){
        self::$debug=true;
        return $this;
    }
    function unDebug(){
        self::$debug=false;
    }

    function deCut(){
        self::$cut=true;
        return $this;
    }
    function unDeCut(){
        self::$cut=false;
    }


    function rowSelect($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;
        //echo $query;//exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }//测试

        $tableRow=$this->get_row( $this->prepare( $query ));

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$tableRow,'return'); }//测试
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }

    function resultSelect($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;
        //print_r($query);exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }//测试


        $tableRow=$this->get_results( $this->prepare( $query ));

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$tableRow,'return'); }//测试
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }

    function rowInsert($table,$dataArray){

        if( is_object($dataArray) ) $dataArray=$this->obj2arr($dataArray);
        elseif( is_string($dataArray) ){ $dataArray=$this->commaStr2Arr($dataArray);}
        //var_dump($dataArray);//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$dataArray,'start'); }//测试

        $tableInsert=$this->insert($table,$dataArray);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$tableInsert,'return'); }//测试
        if($tableInsert){
            return true;
        }else{
            return false;
        }
    }

    function rowUpdate($table,$dataArray,$whereArray){

        if( is_object($dataArray) ) $dataArray=$this->obj2arr($dataArray);
        elseif( is_string($dataArray) ){ $dataArray=$this->commaStr2Arr($dataArray);}

        if( is_string($whereArray) ){ $whereArray=$this->commaStr2Arr($whereArray);}
        //print_r($whereArray);//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$whereArray,'start'); }//测试

        $rowUpdate=$this->update($table,$dataArray,$whereArray);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowUpdate,'return'); }//测试

        if($rowUpdate){
            return true;
        }else{
            return false;
        }

    }

    function rowDel($tableArray,$whereArray='',$orderArray=''){

        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query='DELETE'.$tableMade.$whereMade.$orderMade;
        //echo($query);//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$whereArray,'start'); }//测试

        $rowDel=$this->query($this->prepare( $query ));

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowDel,'return'); }//测试
        return $rowDel;

    }


    protected function tableMade($tableArray=''){

        return ' FROM `'.$tableArray.'`';
    }

    protected function selectMade($selectArray=''){

        if(empty($selectArray)||$selectArray=='*'){ return ' SELECT *'; }

        if(is_array($selectArray)||is_object($selectArray)){

            $select='';$s='';
            foreach ($selectArray as $k=>$v){
                $s++;
                if( $s==count($selectArray) ){
                    $select.= "`".$v."`";
                }else{
                    $select.= "`".$v."`".',';
                }
            }

            return ' SELECT '.$select;
        }

        if(is_string($selectArray)){

            preg_match_all('/[\,]/',$selectArray,$match);
            //var_dump($match[0]);//exit;//

            switch(empty($match[0])){
                case true: return ' SELECT `'.$selectArray.'` '; break;
                case false:

                    $newSelect=explode(',',$selectArray);
                    //var_dump($newSelect);//

                    $newArr=''; $i='';
                    foreach($newSelect as $n=>$v){
                        $i++;
                        if($i==count($newSelect)){
                            $newArr.="`".$v."`";
                        }
                        else{
                            $newArr.="`".$v."`,";
                        }
                    }
                    return ' SELECT '.$newArr;
                    break;
            }

        }

    }

    protected function whereMade($whereArray=''){

        //var_dump($whereArray);//exit;//

        if($whereArray==''||$whereArray=='-'){
            return '';
        }

        if(is_array($whereArray)||is_object($whereArray) ){

            if(!empty($whereArray)){
                $whereArray=$this->forWhereCompare($whereArray);
                //print_r($whereArray);//
                return ' WHERE '.$whereArray;
            }

            return '';

        }

        if(is_string($whereArray) ){
            $whereArray=$this->commaStr2Arr($whereArray);
            $whereArray=$this->forWhereCompare($whereArray);
            //var_dump($whereArray);exit;//
            return ' WHERE '.$whereArray;
        }


    }

    protected function groupByMade($groupByArray=''){

        $queryStr='group by'.' ';

        if(is_array($groupByArray)||is_object($groupByArray)){

            foreach($groupByArray as $n=>$v){
                $queryStr.=$v.' ';
                if(end($groupByArray)==$v){
                    $queryStr.=$v;
                }
            }

            return $queryStr;

        }

        if(is_string($groupByArray)){

            return $queryStr.$groupByArray.' ';

        }

        if(empty($groupByArray)||$groupByArray=='-'){

            return '';

        }

        return '';

    }

    protected function orderMade($orderArray=''){

        //order limit 通用
        //例：
        //$orderArray['uid']='ASC';
        //$orderArray['limit']='0,5';//

        $s='';
        $order='';
        $orderArrayCount=count($orderArray);

        if(empty($orderArray)||$orderArray=='-'){
            return ' ';
        }

        if(is_array($orderArray)){

            foreach ($orderArray as $k=>$v){
                $s++;
                if( $s==$orderArrayCount ){
                    $order.= ''.$k.' '.$v.' ';
                }else{
                    $order.= ''.$k.' '.$v.' ';
                }
            }

            return ' ORDER BY '.$order;

        }

        if(!is_array($orderArray)){
            return ' ORDER BY '.$orderArray.' ';
        }
    }

    protected function limitMade($orderArray=''){



    }

    protected function setMade($setArray){

        if(empty($setArray) ){
            return '';
        }

        if(is_array($setArray) ){
            $set='';
            foreach ($setArray as $k=>$v){
                if( $v!=end($setArray) ){
                    $set.= $k.'=\''.$v.'\',';
                }else{
                    $set.= $k.'=\''.$v.'\'';
                }
            }
            return ' SET '.$set;
        }

        if(!is_array($setArray)){
            $expArr=explode(',',$setArray);
            //var_dump($expArr);//
            if(!is_array($expArr)) return false;
            return ' SET '.$expArr[0].'= \''.$expArr[1].'\'';
        }

        return false;

    }

    protected function insertMade($dataArray){

        $keys='';$values='';$n=0;
        foreach($dataArray as $k=>$v){
            $n++;
            if(count($dataArray)!=$n){
                $keys.=$k.',';
                $values.="'".$v."',";
            }
            else{
                $keys.=$k.'';
                $values.="'".$v."'";
            }
        }

        $arr['keys']=$keys;
        $arr['values']=$values;

        //var_dump($arr);//
        return $arr;
    }


    //used **2
    protected function commaStr2Arr($commaStr){

        $commaStr=explode(',',$commaStr);
        //var_dump($commaStr);//

        $newArr=[];
        foreach($commaStr as $n=>$v){
            $v=explode('/',$v);
            $newArr[$v[0]]=$v[1];
        }
        //var_dump($newArr);exit;//

        return $newArr;

    }

    protected function forWhereCompare($whereArray){
        $i='';
        $count=count($whereArray);
        $where='';
        foreach ($whereArray as $k=>$v){
            $i++;
            //var_dump($k);
            preg_match_all("/\>|\<|\=|\>\=|\<\=|\!\=|\%\%|\%\=|\=\%/",$k,$matchs);

            $separatorA='\'';$separatorB='\'';

            if(isset($matchs[0][0])){
                //var_dump($matchs);//
                $nk=explode('/',$k);
                //print_r($nk);//

                //print_r($v);echo'||c||';
                //print_r($count);echo'||d||';//exit;


                //wpdb中 prepare()使用了vprintf()函数, %s 会被替换掉，故写成 %%s 就能执行
                switch($nk[1]){
                    case '%%' :$nk[1]='LIKE';$separatorA='\'%%';$separatorB='%%\'';break;
                    case '%=' :$nk[1]='LIKE';$separatorA='\'%%';break;
                    case '=%' :$nk[1]='LIKE';$separatorB='%%\'';break;
                }

                if( $i==$count ){ $where.= $nk[0].' '.$nk[1].$separatorA.$v.$separatorB.' '; }
                else{ $where.= $nk[0].' '.$nk[1].$separatorA.$v.$separatorB.' AND '; }
            }
            else{

                //print_r($i);echo'||a||';
                //print_r($count);echo'||b||';//exit;

                if( $i==$count ){ $where.= $k.'=\''.$v.'\' '; }
                else{ $where.= $k.'=\''.$v.'\' AND '; }
            }

        }
        return $where;
    }

    protected function obj2arr($dataArray){

        if(is_object($dataArray)){
            $newData=[];
            foreach($dataArray as $k=>$v){
                $newData[$k]=$v;
            }
            return $newData;
        }

        return false;
    }


////////////////////


//检查式 新增条目
    function rowAddCheck($table,$selectArray,$whereArray,$dataArray,$orderArray=''){

        $rowSelect=$this->rowSelect($table,$selectArray,$whereArray,$orderArray);
        //var_dump($rowSelect);exit;//

        if($rowSelect){ $have='1'; }else{ $have='0'; }

        if($have=='0'){
            $rowInsert=$this->rowInsert($table,$dataArray);

            if($rowInsert){ return 'insertOk'; }else{ return 'insertFail'; }

        }else{
            $rowUpdate=$this->rowUpdate($table,$dataArray,$whereArray);

            if($rowUpdate){ return 'updateOk'; }else{ return 'updateFail'; }
        }

    }

//加减表中某字段的值,可自定步进值,根据 $selectArray传参类型 返回同类型处理结果
    function fieldNumSUM($table,$selectArray='',$whereArray,$math='1',$num='1',$orderArray=''){


        //print_r($selectArray);print_r($whereArray);exit;

        $rowSelect=$this->rowSelect($table,$selectArray,$whereArray,$orderArray);
        if(!$rowSelect){
            exit('$rowSelect fail');
        }

        //print_r($rowSelect);exit;

        //加减数据判断 函数
        $dataArray=$this->dataArrayGet($rowSelect,$selectArray,$math,$num);
        //print_r($dataArray);exit;

        $rowUpdate=$this->rowUpdate($table,$dataArray,$whereArray);
        if($rowUpdate){ return $dataArray; }
        else{ return false; }
    }
    protected function dataArrayGet($rowSelect,$selectArray,$math='1',$num='1'){
        $dataArray=array();
        if(is_array($selectArray)&&is_array($num)){

            //print_r($rowSelect);print_r($selectArray);exit;

            foreach($selectArray as $k=>$v){

                $rowSelectV=$rowSelect->$v;
                $numV=$num[$k];
                //print_r($rowSelectV);exit;

                if($math=='1'){
                    $addField=$rowSelectV + $numV;
                }else{
                    $addField=$rowSelectV - $numV;
                }
                $dataArray[$v]=$addField;
            }
            //print_r($dataArray); exit;
        }else{

            $rowSelectA=$rowSelect->$selectArray;

            if($rowSelectA=='0'){
                $addField='0';
            }else if($math=='1'){
                $addField=$rowSelectA + $num;
            }else{
                $addField=$rowSelectA - $num;
            }
            $dataArray[$selectArray]=$addField;
            //print_r($dataArray); exit;
        }

        return $dataArray;
    }

//按组查询 并给每个组 追加数据
    function resultByType($table,$select,$where,$order,$group){

        $select=self::selectMade($select);
        $table=self::tableMade($table);
        $order=self::orderMade($order);
        $group_str=self::groupByMade($group);

        $select_type=self::selectMade($group);
        $typeQuery=$select_type.$table.$group_str;
        //echo($typeQuery);//
        $typeArray=$this->get_results($this->prepare( $typeQuery ));
        //print_r($typeArray);//

        $resultArray=array();

        foreach($typeArray as $n=>$v ){

            if($where!=''||$where!='-'){
                $where_str=self::whereMade( 'type/'.$v->type.','.$where );
            }
            else{
                $where_str=self::whereMade( 'type/'.$v->type );
                //var_dump($where_str);exit;//
            }

            $resultQuery=$select.$table.$where_str.$order;

            //echo($resultQuery).'<br/>';//

            $resultArray[$n][$group]=$v->type;
            $resultArray[$n][$group.'_info']=$this->get_results($this->prepare( $resultQuery ))  ;
        }

        //print_r($resultArray);exit;//

        return $resultArray;

    }

//获取现在时间 和 字段时间 间隔
    function haveSomeSecond($table,$uid,$second,$time=''){

        if($time==''){ $time='time';}

        $havePostTime=$this->rowSelect( $table,$time,array('uid'=>$uid),array('time'=>'desc') );

        //var_dump($havePostTime);//

        if($havePostTime && time() < ($havePostTime->time+($second*60)) ){

            return true;

        }

        return false;

    }

//查询时自动加值
    function rowSelectMath($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMathMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;

        //echo $query;exit;//

        $tableRow=$this->get_row( $this->prepare( $query ));
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }
    protected function selectMathMade($selectArray=''){

        if($selectArray!=''&& is_array($selectArray)){
            $select=self::forSelectAdd($selectArray);
            //print_r($select);exit;//
            return ' SELECT '.$select;

        }elseif($selectArray!=''&& $selectArray!='*'){
            $selectArray=explode(',',$selectArray);
            $select=self::forSelectAdd($selectArray);
            //print_r($select);exit;//
            return ' SELECT '.$select;
        }else{
            return ' SELECT *';
        }

    }
    protected function forSelectAdd($selectArray){
        $select='';
        foreach ($selectArray as $k=>$v){

            preg_match_all("/\+|\-|\*|\//",$v,$matchs);

            $math=$matchs[0][0];
            //print_r($math);exit;//

            $nv=explode($math,$v);
            //var_dump($nv);exit;//
            if(isset($nv[1]) ){
                if( $v!=end($selectArray) ){
                    $select.= $nv[0].' '.$math.$nv[1].' as '.$nv[0].',';
                }else{
                    $select.= $nv[0].' '.$math.$nv[1].' as '.$nv[0];
                }
            }else{
                if( $v!=end($selectArray) ){
                    $select.= $nv[0].',';
                }else{
                    $select.= $nv[0];
                }
            }
        }
        return $select;
    }





}