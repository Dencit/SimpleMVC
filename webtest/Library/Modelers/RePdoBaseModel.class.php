<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/29  Time: 10:18 */

namespace Modelers;

use Debugs\testTool as tsTool;

use \NoSql\RedisDB as redis;

class RePdoBaseModel extends PdoDB{

    protected  static $REDIS;
    protected  static $ttl;
    protected  static $redis;
    protected  static $just;
    protected  static $reSign;

    function __construct($DB=null,$allWay=null){
        parent::__construct($DB,$allWay);

        self::$REDIS=$DB->REDIS;
        $redis=new redis(self::$REDIS);
        self::$redis=$redis;

    }

    protected function redisReConnect($REDIS){
        $redis=new redis($REDIS);
        self::$redis=$redis;
    }

    function option($optStr=null){

        if(!empty($optStr)){
            $optArr=$this->commaStr2Arr($optStr);
            //print_r($optArr);//
            foreach($optArr as $k=>$v){
                //echo $k.'  |  '.$v.'<br/>';//
                switch($k){
                    case'just': //一次有效
                        self::$just=$v; break;
                    case'sec': //一次有效
                        self::$REDIS->TIMEOUT=$v;
                        //var_dump(self::$REDIS);exit;//
                        $this->redisReConnect(self::$REDIS);
                        break;
                    case'ttl'://一直有效 必须重置
                        self::$ttl=$v; break;
                    case'sign'://一直有效 必须重置
                        self::$reSign='|'.$v.''; break;
                    default: break;
                }
            }
        }else{
            self::$just='';
            self::$reSign='';
        }

        return $this;
    }

    function begin(){
        parent::begin();
    }

    function rollback(){
        parent::rollback();
    }

    function commit(){
        parent::commit();
    }


    function rowSelect($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;

        //echo $query;//exit;//
        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); self::$redis->debug(); }

        ////redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableSign=$this->reKeyTableSign($tableArray);
        $reTable=$tableSign.self::$reSign; $reField=__METHOD__.'::'.$query;
        if( self::$just!=1 ){
            $reRowSelect=self::$redis->reRowSelect($reTable,$reField);
            //$reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
            //var_dump($reRowSelect);exit;//
            if(!$reRowSelect){
                $rowSelect=$this->preQuery($query,'row');//pdo
                //var_dump($rowSelect);exit;//
                if($rowSelect){
                    $reRowInsert=self::$redis->reRowInsert($reTable,$reField,$rowSelect,self::$ttl);
                    if($reRowInsert){
                        self::$ttl='';
                        $reRowSelect=self::$redis->reRowSelect($reTable,$reField);
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }


            $rowSelect=$reRowSelect;
            self::$just='';//

        }
        else{
            $rowSelect=$this->preQuery($query,'row');//pdo
            $reRowUpdate=self::$redis->reRowUpdate($reTable,$reField,$rowSelect,self::$ttl);
            if($reRowUpdate){
                self::$ttl='';
                $reRowSelect=self::$redis->reRowSelect($reTable,$reField);
                $rowSelect=$reRowSelect;
            }
            self::$just='';//
        }
        //\\redis

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowSelect,'return'); }//测试

        if( !empty($rowSelect) ){
            return $rowSelect;
        }else{
            $this->rollback();
            return false;
        }

    }

    function resultSelect($tableArray,$selectArray='',$whereArray='',$orderArray='',$groupArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $groupMade=$this->groupByMade($groupArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$groupMade.$orderMade;


        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }//测试

        ////redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableSign=$this->reKeyTableSign($tableArray);
        $reTable=$tableSign.self::$reSign; $reField=__METHOD__.'::'.$query;
        if( self::$just!=1 ){
            $reRowSelect=self::$redis->reRowSelect($reTable,$reField);
            //$reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
            //var_dump($reRowSelect);exit;//
            if(!$reRowSelect){
                $resultSelect=$this->preQuery($query,'class');//pdo
                //var_dump($resultSelect);exit;
                if($resultSelect){
                    $reRowInsert=self::$redis->reRowInsert($reTable,$reField,$resultSelect,self::$ttl);
                    if($reRowInsert){
                        self::$ttl='';
                        $reRowSelect=self::$redis->reRowSelect($reTable,$reField);

                    }else{
                        return false;
                    }
                }else{
                    return false;
                }

            }

            $resultSelect=$reRowSelect;
            self::$just='';

        }
        else{
            $resultSelect=$this->preQuery($query,'class');//pdo
            //var_dump($resultSelect);exit;//
            $reRowUpdate=self::$redis->reRowUpdate($reTable,$reField,$resultSelect,self::$ttl);
            if($reRowUpdate){
                self::$ttl='';
                $reRowSelect=self::$redis->reRowSelect($reTable,$reField);
                $resultSelect=$reRowSelect;
            }
            self::$just='';//
        }
        //\\redis

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$resultSelect,'return'); }//测试

        //var_dump($resultSelect);exit;//

        if( !empty($resultSelect) ){
            return $resultSelect;
        }else{
            $this->rollback();
            return false;
        }

    }

    function rowUpdate($table,$dataArray,$whereArray){

        if( is_object($dataArray) ) $dataArray=$this->obj2arr($dataArray);
        elseif( is_string($dataArray) ){ $dataArray=$this->commaStr2Arr($dataArray);}

        if( is_string($whereArray) ){ $whereArray=$this->commaStr2Arr($whereArray);}
        //print_r($whereArray);//

        $whereMade=$this->whereMade($whereArray);
        $setMade=$this->setMade($dataArray);

        $query="UPDATE ".$table.$setMade.$whereMade;
        //var_dump($query);//exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }

        ////redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableSign=$this->reKeyTableSign($table);
        $reTable=$tableSign.self::$reSign; $reField=__METHOD__.'::'.$query;
        $reRowUpdate=self::$redis->reRowUpdate($reTable,$reField,$query,self::$ttl);
        //var_dump($reRowUpdate);exit;//
        if($reRowUpdate){
            self::$ttl='';
            $query=self::$redis->reRowSelect($reTable,$reField);
        }
        //\\redis


        $rowUpdate=$this->preQuery($query);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowUpdate,'return'); }//测试

        if($rowUpdate){

            $reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
            self::$redis->reRowDel($reTable);//清理该表的查询缓存
            if(!$reRowDel){ return false; }//清理redis键表

            return true;
        }else{
            $this->rollback();
            return false;
        }

    }

    function rowInsert($table,$dataArray){

        if( is_object($dataArray) ) $dataArray=$this->obj2arr($dataArray);
        elseif( is_string($dataArray) ){ $dataArray=$this->commaStr2Arr($dataArray);}
        $str=$this->insertMade($dataArray);

        $query="INSERT INTO ".$table."(".$str['keys'].") VALUES(".$str['values'].")";
        //var_dump($query);exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }

        ////redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableSign=$this->reKeyTableSign($table);
        $reTable=$tableSign.self::$reSign; $reField=__METHOD__.'::'.$query;
        //$reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
        $reRowInsert=self::$redis->reRowInsert($reTable,$reField,$query,self::$ttl);
        if(!$reRowInsert){
            self::$ttl='';
            $query=self::$redis->reRowSelect($reTable,$reField);//操作语句有重复 获取旧的
        }
        //\\redis

        $rowInsert=$this->preQuery($query);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowInsert,'return'); }//测试

        if($rowInsert){

            $reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
            self::$redis->reRowDel($reTable);//清理该表的查询缓存
            if(!$reRowDel){ return false; }//清理redis键表

            return true;

        }else{
            $this->rollback();
            return false;
        }
    }

    function rowDel($tableArray,$whereArray='',$orderArray=''){

        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query='DELETE'.$tableMade.$whereMade.$orderMade;
        //echo($query);//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }

        ////redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableSign=$this->reKeyTableSign($tableArray);
        $reTable=$tableSign.self::$reSign; $reField=__METHOD__.'::'.$query;
        //$reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
        $reRowInsert=self::$redis->reRowInsert($reTable,$reField,$query,self::$ttl);
        if(!$reRowInsert){
            self::$ttl='';
            $query=self::$redis->reRowSelect($reTable,$reField);//操作语句有重复 获取旧的
        }
        //\\redis


        $rowDel=$this->preQuery($query);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowDel,'return'); }//测试

        if($rowDel){

            $reRowDel=self::$redis->reRowDel($reTable,$reField); //var_dump($reRowDel);//
            self::$redis->reRowDel($reTable);//清理该表的查询缓存
            if(!$reRowDel){ return false; }//清理redis键表
            return true;

        }else{
            $this->rollback();
            return false;
        }

    }

    function joinSelect($tableArray,$selectArray='',$whereArray='',$orderArray='',$groupArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $groupMade=$this->groupByMade($groupArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$groupMade.$orderMade;
        //var_dump($query);exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$query,'start'); }//测试

        //redis
        if(self::$debug){ self::$redis->debug(); }//测试
        $tableArr=$this->reKeyTableSign($tableArray);
        //var_dump($tableArr);exit;//
        //redis

        $rowSelect=$this->preQuery($query,'class');


        if(self::$debug){ tsTool::debugMsg(__METHOD__,$rowSelect,'return'); }//测试

        if( !empty($rowSelect[0]) ){
            return $rowSelect;
        }else{
            return false;
        }


    }


    function reTableFields($table){
        $reTable=$table.self::$reSign;
        return self::$redis->reTableKeys($reTable);
    }

    function reTableTTL($table){

        $reTable=$table.self::$reSign;
        $reTableTtl=self::$redis->reTableTtl($reTable);

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$reTableTtl,'normal'); }//测试
        return $reTableTtl;
    }

    protected function reIn(){

    }
    protected function reOut(){

    }


    protected function tableMade($tableArray=''){

        //table join 表达式判断 并转换成数组结构 符合接下来的转换 规则
        @preg_match('/(,)/i',$tableArray,$match);
        //var_dump($match);exit;//
        if(!empty($match[0]) ){
            $tableArray=$this->commaStr2ArrTB($tableArray);
        }
        //var_dump($tableArray);exit;//


        //table join 数组转换
        $tableStr=''; $i='0';
        if(is_array($tableArray)||is_object($tableArray)){

            //table join 数组转换 join on 引用
            $joinType='';$joinOn='';
            if(isset($tableArray['_join']) ){
                $joinType=$tableArray['_join'];
                unset($tableArray['_join']);
            }

            if(isset($tableArray['_on']) ){
                $joinOn=' ON '.$tableArray['_on'];
                unset($tableArray['_on']);
            }
            //var_dump($tableArray);exit;//

            //table join 数组转换 table 转换
            foreach($tableArray as $k=>$v){
                $i++;$ii=($i-1);
                if($i!=count($tableArray)){
                    $tableStr.='`'.$k.'` '.$v.' '.$joinType[$ii].' JOIN ';
                }else{
                    $tableStr.='`'.$k.'` '.$v.$joinOn;
                }
            }

        }

        //最后 才到 单字符串 的情况
        if(is_string($tableArray)){
            $tableStr='`'.$tableArray.'`';
        }

        return ' FROM '.$tableStr.' ';
    }

    //redis 获取传入表格名 数组 或字符串 或表达式 专用
    protected function reKeyTableSign($tableStr){

        if(is_string($tableStr)){

            @preg_match('/(,)/i',$tableStr,$match);
            //var_dump($match);exit;//
            if(!empty($match[0]) ){
                $tableStr=$this->commaStr2ArrTB($tableStr);
            }

        }

        if(is_array($tableStr)||is_object($tableStr)){
            unset($tableStr['_join']);unset($tableStr['_on']);

            $str='';$i='0';
            foreach($tableStr as $k=>$v){
                $i++;
                if($i!=count($tableStr)){ $str.=$k.','; }
                else{ $str.=$k; }
            }

            $tableStr=$str;
        }

        //var_dump($tableStr);exit;//
        return $tableStr;

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

            $select=$this->commaStrFormat($selectArray);
            //var_dump($select);exit;//

            return ' SELECT '.$select;

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

        if(empty($groupByArray)||$groupByArray=='-'){

            return '';

        }

        $queryStr=' ';
        if(is_string($groupByArray)){

            return $queryStr.$groupByArray.' ';

        }

        if(is_array($groupByArray)||is_object($groupByArray)){

            $kArr=array_keys($groupByArray);
            $k=$kArr[0];
            $v=$groupByArray[$k];

            //var_dump($k);var_dump($v);exit;//

            $vv=$this->dotMatch($v);//处理 联表查询带点的 子字段

            return $k.' BY '.$vv;
        }


        return '';

    }

    protected function orderMade($orderArray=''){

        /*
        order limit 通用
        例：
        $orderArray['uid']='ASC';
        $orderArray['limit']='0,5';
        */

        if(empty($orderArray)||$orderArray=='-'){
            return ' ';
        }

        if(is_array($orderArray)||is_array($orderArray)){

            $kArr=array_keys($orderArray);
            //var_dump($kArr);exit;//

            $orderK=$this->dotMatch($kArr[0]);

            $orderStr=' ORDER BY '.$orderK;
            if(isset($kArr[1])){
                $limitK=$kArr[1];
                $orderStr.=' LIMIT '.$orderArray[ $limitK ] ;
            }

            return $orderStr;

        }

        if(is_string($orderArray)){
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
    protected function dotMatch($v){
        //处理 联表查询带点的 子字段
        preg_match("/(\.)/i",$v,$match);
        //var_dump($match);exit;//
        if(!empty($match[0])){
            $vv=preg_replace('/(\w+)\.(\w+)$/i','$1.`$2`',$v);
        }else{
            $vv='`'.$v.'`';
        }
        return $vv;
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

    //used *1
    protected function commaStr2ArrTB($commaStr){

        $commaStr=explode(',',$commaStr);
        //var_dump($commaStr);//

        $newArr=[];$newKeyArr=[];$commaArr=array('_table','_join','_on');

        foreach($commaStr as $n=>$v){
            $newKeyArr[$commaArr[$n]]=$v;
        }

        foreach($newKeyArr as $k=>$v) {
            $vv = explode('/', $v);
            if (is_array($vv)) {

                foreach($vv as $y=>$z){

                    if ($k == '_table') {
                        preg_match('/(\S+)\s+(\S+)/i',$z,$match);
                        //var_dump($match);exit;//
                        if(!empty($match[0])){
                            $zz=array($match[1]=>$match[2]);
                            //var_dump($zz);exit;//
                            foreach($zz as $i=>$r){
                                $newArr[$i]=$r;
                            }
                        }
                    }
                    else if($k == '_on'){ $newArr['_on']=$z; }
                    else{ $newArr[$k]=$vv; }

                }
                //var_dump($newArr);exit;//
            }

        }

        //var_dump($newArr);exit;//

        return $newArr;

    }

    protected function commaStrFormat($selectArray){

        preg_match('/[\,]/',$selectArray,$match);
        //var_dump($match[0]);exit;//

        switch(empty($match[0])){
            case true: return $selectArray; break;
            case false:

                $newSelect=explode(',',$selectArray);
                //var_dump($newSelect);//

                $newArr=''; $i='';
                foreach($newSelect as $n=>$v){
                    $i++;

                    preg_match("/\./i",$v,$match);
                    //var_dump($match);exit;//

                    if(!empty($match[0])){ $vv=preg_replace("/^(\w+\.)(\S+)$/i",'$1`$2`',$v); }
                    else{ $vv='`'.$v.'`'; }

                    if($i==count($newSelect)){ $newArr.=$vv; }
                    else{ $newArr.=$vv.","; }

                }
                return $newArr;
                break;
        }

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


////检查式 新增条目
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
//\\

////加减表中某字段的值,可自定步进值,根据 $selectArray传参类型 返回同类型处理结果
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
//\\

////按组查询 并给每个组 追加数据
    function resultByType($table,$select,$where,$order,$group){

        $select=self::selectMade($select);
        $table=self::tableMade($table);
        $order=self::orderMade($order);
        $group_str=self::groupByMade($group);

        $select_type=self::selectMade($group);
        $typeQuery=$select_type.$table.$group_str;
        //echo($typeQuery);//
        $typeArray=$this->prepare($typeQuery,'class');
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

            $resultArray[$n][$group]=$v->type;//$this->prepare($resultQuery,'class');
            $resultArray[$n][$group.'_info']=$this->prepare($resultQuery,'class') ;
        }

        //print_r($resultArray);exit;//

        return $resultArray;

    }
//\\

////获取现在时间 和 字段时间 间隔
    function haveSomeSecond($table,$uid,$second,$time=''){

        if($time==''){ $time='time';}

        $havePostTime=$this->rowSelect( $table,$time,array('uid'=>$uid),array('time'=>'desc') );

        //var_dump($havePostTime);//

        if($havePostTime && time() < ($havePostTime->time+($second*60)) ){

            return true;

        }

        return false;

    }
//\\

////查询时自动加值
    function rowSelectMath($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMathMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;

        //echo $query;exit;//

        $tableRow=$this->prepare($query,'row') ;
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }
    //for rowSelectMath()
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
    //for selectMathMade()
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
//\\



} 