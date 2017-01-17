<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/10/28  Time: 09:49 */

namespace NoSql;

use Debugs\testTool as tsTool;

class RedisDB {

    protected static $debug;
    protected static $cut;

    public static $con;
    private static $PREFIX;
    private static $TIMEOUT;

    private static $trans;

    function __construct($REDIS=null){

        self::$PREFIX=$REDIS->PREFIX;

        $con = new \redis();
        $con->connect($REDIS->HOST,$REDIS->PORT);
        $con->auth($REDIS->AUTH);
        $con->select(1);
        self::$con=$con;

        self::$TIMEOUT=$REDIS->TIMEOUT;//过期时间

    }


    function debug(){ self::$debug=true; return $this; }
    function unDebug(){ self::$debug=false; }
    function deCut(){ self::$cut=true; return $this; }
    function unDeCut(){ self::$cut=false; }


////事务工具

    //开始事务 标记一个事务块的开始。
    function begin(){
        self::$trans='';
        self::$trans=self::$con->multi();

        if(self::$debug){ tsTool::debugMsg(__METHOD__,self::$trans,'start'); }//测试
    }
    //取消事务，放弃执行事务块内的所有命令。
    function rollBack(){
        $res=self::$trans->discard();
        self::$trans='';

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$res,'return'); }//测试
        return $res;
    }
    //自动提交 执行所有事务块内的命令。
    function commit(){
        $res=self::$trans->exec();
        self::$trans='';

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$res,'return'); }//测试
        return $res;
    }

    //监视一个(或多个) key
    function watch($table){
        $forOrd=$this->forOrd($table);//var_dump($forOrd);exit;//
        $table=$forOrd['table'];

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table,'normal'); }//测试
        return self::$trans->watch($table);
    }
    //取消 WATCH 命令对所有 key 的监视。
    function unWatch($table){
        $forOrd=$this->forOrd($table);//var_dump($forOrd);exit;//
        $table=$forOrd['table'];

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table,'normal'); }//测试
        return self::$trans->unwatch($table);
    }

//\\事务工具



////hash键值对集合 json数据型

    //键表内 字段行新增
    function reRowInsert($table,$row=null,$data=null,$ttl=null){

        $forOrd=$this->forOrd($table,$row);//var_dump($forOrd);exit;//
        $table=$forOrd['table'];$row=$forOrd['row'];

        $fields='';
        if(is_array($data)||is_object($data)){
            $fields=$this->arrObj2json($data);
        }else{
            $fields=$data;
        }

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table.'<br/>'.$row.'<br/>'.$fields,'start'); }//测试

        if(!empty(self::$trans)){
            $reCon=self::$trans;
            $hSetNx= $reCon->hSetNx($table,$row,$fields);//不覆盖的关键
            if(!$hSetNx->socket){ $this->rollBack(); return false; }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$hSetNx,'return'); }//测试
            return true;
        }
        else{
            $reCon=self::$con;
            $hSetNx= $reCon->hSetNx($table,$row,$fields);//不覆盖的关键
            if(!$hSetNx){ return false; }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$hSetNx,'return'); }//测试
        }

        if( !empty($ttl) ){ $reCon->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; $reCon->expire($table,$ttl); }


        if(self::$debug){ tsTool::debugMsg(__METHOD__,$ttl,'normal'); }//测试
        return true;

    }
    //键表内 批量字段行新增
    function reResInsert($table,$rowData=null,$ttl=null){

        foreach($rowData as $n=>$v){
            $reRowInsert=$this->reRowInsert($table,$n,$v,$ttl);
            if(!$reRowInsert){ return false; }
        }

        if(!empty(self::$trans)){ $reCon=self::$trans;  }
        else{ $reCon=self::$con; }

        if( !empty($ttl) ){ $reCon->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; $reCon->expire($table,$ttl); }
        return true;
    }

    //键表内 字段行更新
    function reRowUpdate($table,$row=null,$data=null,$ttl=null){

        $forOrd=$this->forOrd($table,$row);//var_dump($forOrd);exit;//
        $table=$forOrd['table'];$row=$forOrd['row'];

        $fields='';
        if(is_array($data)||is_object($data)){
            $fields=$this->arrObj2json($data);
        }else{
            $fields=$data;
        }

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table.'<br/>'.$row.'<br/>'.$fields,'start'); }//测试

        if(!empty(self::$trans)){
            $reCon=self::$trans;
            $hSet=$reCon->hMSet($table,array($row=>$fields) );//覆盖的关键
            if(!$hSet->socket){ $this->rollBack(); return false; };

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$hSet,'return'); }//测试
            return true;
        }
        else{
            $reCon=self::$con;
            $hSet=$reCon->hMSet($table,array($row=>$fields) );//覆盖的关键
            if(!$hSet){ return false; };

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$hSet,'return'); }//测试
        }

        if( !empty($ttl) ){ $reCon->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; $reCon->expire($table,$ttl); }

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$ttl,'normal'); }//测试
        return true;

    }
    //键表内 批量字段行更新
    function reResUpdate($table,$rowData=null,$ttl=null){
        $forOrd=$this->forOrd($table);//var_dump($forOrd);exit;//
        $table=$forOrd['table'];

        foreach($rowData as $n=>$v){

            if(is_array($v)||is_object($v)){
                $rowData[$n]=$this->arrObj2json($v);
            }
        }


        if(!empty(self::$trans)){
            $reCon=self::$trans;
            $hMset= $reCon->hMset($table,$rowData );//覆盖的关键
            if(!$hMset->socket){ $this->rollBack(); return false; };
            return true;
        }
        else{
            $reCon=self::$con;
            $hMset= $reCon->hMset($table,$rowData );//覆盖的关键
            if(!$hMset){  return false; };
        }


        if( !empty($ttl) ){ $reCon->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; $reCon->expire($table,$ttl); }
        return true;

    }

    //键表内 字段行数据获取
    function reRowSelect($table,$row=null){
        //var_dump($table);var_dump($row);exit;//
        $forOrd=$this->forOrd($table,$row);
        $table=$forOrd['table'];$row=$forOrd['row'];
        //print_r($forOrd);exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table.'<br/>'.$row,'start'); }//测试

        if(!empty(self::$trans)){
            $reCon=self::$trans;
            $hSelect=$reCon->hGet($table,$row);
            if(!$hSelect->socket){ $this->rollBack(); return false; }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$hSelect,'return'); }//测试
            return true;
        }
        else{
            $reCon=self::$con;
            $hSelect=$reCon->hGet($table,$row);
            if(!$hSelect){ return false; }

            $array=json_decode($hSelect,JSON_UNESCAPED_UNICODE);
            foreach($array as $k=>$v){
                if( is_array($v) ){
                    $array[$k]=$this->array2object($v);
                }
                else{ $array[$k]=$v;}
            }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$array,'return'); }//测试
            return $array;
        }

    }
    //键表内 批量字段行数据获取
    function reResSelect($table,$row=null){

        $forOrd=$this->forOrd($table); //var_dump($forOrd);exit;//
        $table=$forOrd['table'];

        if(!empty($row)){
            foreach($row as $n=>$m){
                $forOrd=$this->forOrd($table,$m); //var_dump($forOrd);exit;//
                $row[$n]=$forOrd['row'];
            }
        }

        //var_dump($row);exit;//

        $hSelect='';
        if(!empty(self::$trans)){
            $reCon=self::$trans;
            if( empty($row) ){ $hSelect=$reCon->hGetAll($table); }
            if( is_array($row) ){ $hSelect=$reCon->hMGet($table,$row); }
            if( !$hSelect->socket ){ $this->rollBack(); return false; }
            return true;
        }
        else{
            $reCon=self::$con;
            if( empty($row) ){ $hSelect=$reCon->hGetAll($table); }
            if( is_array($row) ){ $hSelect=$reCon->hMGet($table,$row); }
            if( !$hSelect ){ return false; }

            $array=[];
            foreach($hSelect as $n=>$m) {
                $mData=json_decode($m,JSON_UNESCAPED_UNICODE);
                foreach ($mData as $k => $v) {
                    if (is_array($v)) {
                        $mData[$k] = $this->array2object($v);
                    } else {
                        $mData[$k] = $v;
                    }
                }
                $array[$n]=$mData;
            }
            return $array;
        }

    }

    //键表内 删除行字段
    function reRowDel($table,$row=null){
        $forOrd=$this->forOrd($table,$row);
        $table=$forOrd['table'];

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$table,'start'); }//测试

        if(!empty(self::$trans)){
            $reCon=self::$trans;
            if(empty($row)){ $del=$reCon->del($table); }
            else{ $row=$forOrd['row']; $del=$reCon->hDel($table,$row); }
            if(!$del->socket){ $this->rollBack(); return false; }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$table.'<br/>'.$row.'<br/>'.print_r($del),'return'); }//测试
            return true;
        }
        else{
            $reCon=self::$con;
            if(empty($row)){ $del=$reCon->del($table); }
            else{ $row=$forOrd['row']; $del=$reCon->hDel($table,$row); }
            if(empty($del)){ return false; }

            if(self::$debug){ tsTool::debugMsg(__METHOD__,$table.'<br/>'.$row.'<br/>'.print_r($del),'return'); }//测试
            return true;
        }
    }
    //键表内 批量删除行字段
    function reResDel($table,$rowData=null){

        foreach($rowData as $n=>$v){

            $forOrd=$this->forOrd($table,$v);
            $table=$forOrd['table'];

            $reRowDel=$this->reRowDel($table,$v);
            if(!$reRowDel){ return false; }
        }
        return true;
    }

    //获取键表内 某行字段是否存在
    function reRowFind($table,$row){

        $forOrd=$this->forOrd($table,$row);
        $table=$forOrd['table'];$row=$forOrd['row'];

        $hExists=self::$con->hExists($table,$row);
        if(!$hExists){ return false; }
        return true;
    }
    //获取键表内 字段行数
    function reTableRows($table){
        $forOrd=$this->forOrd($table);
        $table=$forOrd['table'];

        $hLen=self::$con->hLen($table);
        return $hLen;
    }
    //检查键表内 字段行名称
    function reTableKeys($table){
        $forOrd=$this->forOrd($table);
        $table=$forOrd['table'];
        //var_dump($table);exit;//

        $hKeys=self::$con->hKeys($table);
        return $hKeys;
    }
    //获取键表 剩余过期时间
    function reTableTtl($table){
        $forOrd=$this->forOrd($table);
        $table=$forOrd['table'];

        $ttl=self::$con->ttl($table);
        return $ttl;
    }

    //query元素 转换成键表和字段标记
    protected function forOrd($reTable=null,$reField=null){

        $arr=[];
        if(!empty($reTable)){
            $reTable='['.$this->triMall($reTable).']';
            $arr['table']=addslashes( $reTable.$this->usrSign() );
        }
        if(!empty($reField)){
            $reField=addslashes( $this->triMall($reField) );
            $arr['row']=$reField;
        }

        //var_dump($arr);//exit;

        return $arr;
    }

    //用户短时标记 通过ip和浏览器 判断
    protected function usrSign(){
        $agent=$_SERVER['HTTP_USER_AGENT'];
        $ip=$this->ip();
        return $this->triMall( addslashes('-['.$agent.']-['.$ip.']') ) ;
    }

    //给键表名 加前缀
    protected function prefTable($table,$num=null){
        if( empty($num) ){ $num='';} else{ $num=':'.$num; };
        $prefTable=self::$PREFIX.$table.$num;
        return $prefTable;
    }

//\\hash键值对集合 json数据型



////////////////

    //获取ip
    protected function ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = '';
        }
        preg_match("/[\d\.]{7,15}/", $ip, $ips);
        $ip = isset($ips[0]) ? $ips[0] : 'unknown';
        return $ip;
    }
    //删除字符串空格
    protected function triMall($str)
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        return str_replace($qian,$hou,$str);
    }
    //数组表达式转数组
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
    //数组转json
    protected function arrObj2json($arrObj){
        if (is_object($arrObj) || is_array($arrObj)) {
            return json_encode($arrObj,JSON_UNESCAPED_UNICODE);
        }
    }
    //数组第二层转换
    protected function object2array($obj) {
        if (is_object($obj)) {
            $array=new \ArrayObject();
            foreach ($obj as $key => $val) {
               $array[$key] = $val;
            }
        }else { $array = $obj; }
        return $array;
    }
    protected function array2object($array) {
        if (is_array($array)) {
            $obj = new \StdClass();
            foreach ($array as $key => $val){
                $obj->$key = $val;
            }
        }
        else { $obj = $array; }
        return $obj;
    }
    //数组全部转换
    protected function allObject2array($obj) {
        if (is_object($obj)) {
            $array=new \ArrayObject();
            foreach ($obj as $key => $val) {
                if(is_object($val)){ $obj->$key=$this->allObject2array($val); }
                else{ $array[$key] = $val; }
            }
        }else { $array = $obj; }
        return $array;
    }
    protected function allArray2object($array) {
        if (is_array($array)) {
            $obj = new \StdClass();
            foreach ($array as $key => $val){
                if(is_array($val)){ $obj->$key=$this->allObject2array($val); }
                else{ $obj->$key = $val; };
            }
        }
        else { $obj = $array; }
        return $obj;
    }

//\\\\\\\\\\\\\\\



} 