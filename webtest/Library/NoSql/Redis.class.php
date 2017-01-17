<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/10/28  Time: 09:49 */

namespace NoSql;

class redis {

    public static $con;
    private static $PREFIX;
    private static $TIMEOUT;

    function __construct($REDIS=null){

        self::$PREFIX=$REDIS->PREFIX;

        $con = new \redis();
        $con->connect($REDIS->HOST,$REDIS->PORT);
        $con->auth($REDIS->AUTH);
        $con->select(1);
        self::$con=$con;

        self::$TIMEOUT=$REDIS->TIMEOUT;//过期时间

    }



////事务工具

    //开始事务 标记一个事务块的开始。
    function beginTransaction(){
        self::$con->multi();
    }
    //取消事务，放弃执行事务块内的所有命令。
    function rollBack(){
        self::$con->discard();
    }
    //自动提交 执行所有事务块内的命令。
    function commit(){
        self::$con->exec();
    }

    //监视一个(或多个) key
    function watch($table,$num=null){
        $prefTable=$this->prefTable($table,$num);
        self::$con->watch($prefTable);
    }
    //取消 WATCH 命令对所有 key 的监视。
    function unWatch(){
        self::$con->unwatch();
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

        $hSetNx= self::$con->hSetNx($table,$row,$fields);//不覆盖的关键
        if(!$hSetNx){ $this->rollBack(); return false; }

        if( !empty($ttl) ){ self::$con->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; self::$con->expire($table,$ttl); }

        return true;
    }
    //键表内 批量字段行新增
    function reResInsert($table,$rowData=null,$ttl=null){

        if( !empty($ttl) ){ self::$con->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; self::$con->expire($table,$ttl); }

        foreach($rowData as $n=>$v){
            $reRowInsert=$this->reRowInsert($table,$n,$v,$ttl);
            if(!$reRowInsert){ $this->rollBack(); return false; }
        }
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

        $hSet= self::$con->hMSet($table,array($row=>$fields) );//覆盖的关键

        if(!$hSet){ $this->rollBack(); return false; };

        if( !empty($ttl) ){ self::$con->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; self::$con->expire($table,$ttl); }

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

        $hMset= self::$con->hMset($table,$rowData );//覆盖的关键
        if(!$hMset){ $this->rollBack(); return false; };

        if( !empty($ttl) ){ self::$con->expire($table,$ttl); }
        else{ $ttl=self::$TIMEOUT; self::$con->expire($table,$ttl); }

        return true;
    }

    //键表内 字段行数据获取
    function reRowSelect($table,$row=null){
        //var_dump($table);var_dump($row);exit;//
        $forOrd=$this->forOrd($table,$row);
        $table=$forOrd['table'];$row=$forOrd['row'];
        //print_r($forOrd);exit;//

        $hSelect=self::$con->hGet($table,$row);
        //var_dump($hSelect);exit;//
        if(!$hSelect){ return false; }

        $array='';
        if(is_array($hSelect)||is_object($hSelect)){
            $array=json_decode($hSelect,JSON_UNESCAPED_UNICODE);
            //var_dump($array);exit;//
            foreach($array as $k=>$v){
                if( is_array($v) ){
                    $array[$k]=$this->array2object($v);
                }
                else{ $array[$k]=$v;}
            }
        }
        else{
            $array=$hSelect;
        }

        return $array;
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
        if( empty($row) ){ $hSelect=self::$con->hGetAll($table); }
        if( is_array($row) ){ $hSelect=self::$con->hMGet($table,$row); }
        if( !$hSelect ){return false; }

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

    //键表内 删除行字段
    function reRowDel($table,$row=null){
        $forOrd=$this->forOrd($table,$row);
        $table=$forOrd['table'];

        if(empty($row)){
            $del=self::$con->del($table);
        }else{
            $row=$forOrd['row'];
            $del=self::$con->hDel($table,$row);
        }
        if(empty($del)){ $this->rollBack(); return false; }
        return true;
    }
    //键表内 批量删除行字段
    function reResDel($table,$rowData=null){

        foreach($rowData as $n=>$v){

            $forOrd=$this->forOrd($table,$v);
            $table=$forOrd['table'];

            $reRowDel=$this->reRowDel($table,$v);
            if(!$reRowDel){ $this->rollBack(); return false; }
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
        //var_dump($table);exit;//

        $ttl=self::$con->ttl($table);
        return $ttl;
    }

    //query元素 转换成键表和字段标记
    protected function forOrd($reTable=null,$reField=null){

        $arr=[];
        if(!empty($reTable)){
            $reTable= $this->triMall($reTable) ;
            $arr['table']=addslashes( $reTable.$this->usrSign() );
        }
        if(!empty($reField)){
            $reField=addslashes( $this->triMall($reField) );
            $arr['row']=$reField;
        }
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


////hash键值对集合

    //插入 但重复执行不做覆盖
    function hInsert($table,$data,$num=null){
        $fields='';
        if(is_string($data)){
            $fields=$this->commaStr2Arr($data);
        }
        if(is_array($data)||is_object($data)){
            $fields=[];
            foreach($data as $k=>$v){
                if(is_array($v)||is_object($v)){ $fields[$k]=$this->arrObj2json($v); }
                else{ $fields[$k]=$v;}
            }
        }
        foreach($fields as $k=>$v){
            $hSetNx= self::$con->hSetNx($table,$k,$v);//不覆盖的关键
            if(!$hSetNx){return false;}
        }

        self::$con->expire($table,self::$TIMEOUT);
        return true;
    }
    //更新 一直覆盖
    function hUpdate($table,$data,$num=null){
        $fields='';
        if(is_string($data)){
            $fields=$this->commaStr2Arr($data);
        }
        if(is_array($data)||is_object($data)){
            $fields=[];
            foreach($data as $k=>$v){
                if(is_array($v)||is_object($v)){ $fields[$k]=$this->arrObj2json($v); }
                else{ $fields[$k]=$v;}
            }
        }
        $hMset= self::$con->hMset($table,$fields);//覆盖的关键
        if(!$hMset){ return false; };

        self::$con->expire($table,self::$TIMEOUT);
        return true;
    }
    //获取存储在哈希表中指定字段的值。//获取在哈希表中指定 key 的所有字段和值
    function hSelect($table,$data=null,$num=null){
        $hSelect='';
        if( empty($data) ){ $hSelect=self::$con->hGetAll($table); }
        elseif( is_string($data) ){ $hSelect=self::$con->hGet($table,$data); }
        elseif( is_array($data)||is_object($data) ){
            $hSelect=self::$con->hMGet($table,$data);
        }

        foreach($hSelect as $k=>$v){
            preg_match('/({["\w\s+":"\w\s"|"\w\s+":"\w\s",]+})/',$v,$arr);
            if(empty($v)){
                continue;
            }elseif( !empty($arr[1]) ){
                $vData=json_decode($v,true);
                $hSelect[$k]=$this->array2object($vData);
            }else{ $hSelect[$k]=$v;}
        }
        return $hSelect;
    }
    //键值调换
    function arrayFlip($data){
        $n='-1';$array=[];
        foreach($data as $k=>$v){ $n++; $array[$k]=$n; }
        $array=array_flip($array);//键值调换
        return $array;
    }
    //统计同名键数量，等价于统计表格行数
    function hRowCount($table){
        $numb=0;
        for($num=0; ;$num++){
            $hSelect=self::$con->hGetAll($table);
            if(!empty($hSelect)){ $numb+=1; }
            else{ continue; }
        }
        return $numb;
    }
    //删除键
    function del($table,$num=null){
        $del=self::$con->del($table);
        if(empty($del)){return false;}
        return true;
    }
    //获取哈希表中字段的数量
    function allFieldCount($table,$num=null){
        self::$con->expire($table,self::$TIMEOUT);
        return self::$con->hLen($table);
    }
    //获取所有哈希表中的字段
    function allFieldGet($table,$num=null){
        return self::$con->hKeys($table);
    }
    //查看哈希表 key 中，指定的字段是否存在。
    function fieldExists($table,$field=null,$num=null){
        self::$con->expire($table,self::$TIMEOUT);
        return self::$con->hExists($table,$field);
    }
    //删除一个或多个哈希表字段
    function fieldDel($table,$data,$num=null){
        self::$con->expire($table,self::$TIMEOUT);
        return self::$con->hDel($table,$data);
    }

//\\hash键值对集合


////其他方式补充
    //列表 不检查重复
    function lInsert($table,$data){
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        self::$con->lPush(self::$PREFIX.$table,$data);
    }
    function lSelect($table,$start,$end){
        return self::$con->lRange(self::$PREFIX.$table,$start,$end);
    }

    //无序集合 重复则拒绝
    function sInsert($table,$data){
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        self::$con->sAdd(self::$PREFIX.$table,$data);
    }
    function sSelect($table){
        return self::$con->sMembers(self::$PREFIX.$table);
    }

    //有序集合 重复则拒绝
    function zInsert($table,$data){
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        $num=count( self::$con->zRangeByScore(self::$PREFIX.$table,0,1) );
        self::$con->zAdd(self::$PREFIX.$table,$num,$data);
    }
    function zSelect($table,$start,$end){
        return self::$con->zRangeByScore(self::$PREFIX.$table,$start,$end);
    }

    function set($key,$val=''){

        if(is_array($key)&&$val==''){
            foreach ($key as $k=>$v){
                self::$con->set(self::$PREFIX.$k,$v);
            }
        }else{
            self::$con->set(self::$PREFIX.$key,$val);
        }

    }

    function get($key){

        if(is_array($key)){
            $reArr=array();
            foreach($key as $k=>$v){
                $reArr[$v]=self::$con->get(self::$PREFIX.$v);
            }
            return $reArr;
        }else{
            return self::$con->get(self::$PREFIX.$key);
        }

    }

    function ttl($key){
        return self::$con->ttl(self::$PREFIX.$key);
    }

    function expire($key,$time_out){
        self::$con->expire(self::$PREFIX.$key,$time_out);
    }

    function exists($key){
        return self::$con->exists(self::$PREFIX.$key);

    }
//\\其他方式补充


} 