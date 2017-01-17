<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/23  Time: 14:36 */

namespace Modelers;

use stdClass as stdClass;
use PDO as PDO;
use PDOException as PDOException;

use Debugs\testTool as tsTool;

class PdoDB {

    protected static $debug;
    protected static $cut;

    protected static $DB;
    protected static $COON;
    protected static $real_escape;

    protected static $conSign;

    function __construct($DB=null,$allWay=null){

        self::$DB=$DB;

        $DSN=$DB->DBMS.":host=$DB->HOST;dbname=".$DB->NAME;

        $COON='';
        try {
            $COON= new PDO($DSN,$DB->USER, $DB->PASSWD);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        if( !empty($allWay) ){ PDO::ATTR_PERSISTENT; }//开启长连接

        $COON->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $COON->exec("SET NAMES 'utf8';");
        self::$real_escape=true;
        self::$conSign=$this->signMade(microtime());

        self::$COON=$COON;
    }

    function close(){
        self::$COON=null;
    }


    function debug(){ self::$debug=true; return $this; }
    function unDebug(){ self::$debug=false; }
    function deCut(){ self::$cut=true; return $this; }
    function unDeCut(){ self::$cut=false; }


    function begin(){
        if(self::$debug){ tsTool::debugMsg(__METHOD__,'begin','start'); }//测试
        self::$COON->beginTransaction();
    }

    function rollBack(){
        if(self::$debug){ tsTool::debugMsg(__METHOD__,'rollBack','return'); }//测试
        self::$COON->rollBack();
    }

    function commit(){
        if(self::$debug){ tsTool::debugMsg(__METHOD__,'commit','return'); }//测试
        self::$COON->commit();
    }



//脚本层防注入
    function prepare( $query = null ) { // ( $query, *$args )
        if ( is_null( $query ) )return;

        $args = func_get_args(); array_shift( $args );

        // If args were passed as an array (as in vsprintf), move them up
        if ( isset( $args[0] ) && is_array($args[0]) ) $args = $args[0];

        $query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
        $query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
        $query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s

        array_walk( $args, array( &$this, 'escape_by_ref' ) );

        return @vsprintf( $query, $args );
    }
    function escape_by_ref( &$string ) {
        $string = $this->_real_escape( $string );
    }
    private function _real_escape( $string ) {
        if ( self::$COON && self::$real_escape ){
            return mysql_real_escape_string($string);
        }
        else{
            return addslashes( $string );
        }
    }

//底层防注入
    function preQuery($sql=null,$dataType=null,$num=null){

        preg_match("/(INSERT|UPDATE|SELECT|DELETE)/",$sql,$arr);

        $queryGroup='';
        switch($arr[1]){
            case 'INSERT':$queryGroup=$this->insertGroup($sql); break;
            case 'UPDATE':$queryGroup=$this->updateGroup($sql); break;
            case 'SELECT':$queryGroup=$this->selectGroup($sql); break;
            case 'DELETE':$queryGroup=$this->deleteGroup($sql); break;
        }

        //var_dump($queryGroup);echo '<br/>';//exit;//

        if(self::$debug){ tsTool::debugMsg(__METHOD__,$queryGroup[1],'start'); }//调试用

        $kval=$queryGroup[0];
        $sql=$queryGroup[1];
        $sql=$this->prepare($sql);
        $stmt = self::$COON->prepare($sql);

        $result='';
        switch($arr[1]){
            case 'SELECT':

                if( $this->marchKeyWord($sql,'WHERE')&&!$this->marchKeyWord($sql,'GROUP')  ){
                    $array=$this->forSignArr($kval,'whe_');
                    $this->tryCatch($stmt->execute($array));
                    $result=$this->sqlResult($stmt,$kval,$dataType,$num);
                }
                else{
                    //没有where的情况
                    $this->tryCatch($stmt->execute());
                    $result=$this->sqlResult($stmt,'',$dataType,$num);

                }

                break;
            case 'UPDATE':
                $array=$this->forSignArr($kval,'set_');
                $this->tryCatch($stmt->execute($array));
                $result=$stmt->rowCount();//
                break;
            case 'INSERT':
                $array=$this->forSignArr($kval,'ins_');
                $this->tryCatch($stmt->execute($array));
                $result=self::$COON->lastInsertId();//
                break;
            case 'DELETE':
                $this->forBindParam($kval,$stmt);
                $this->tryCatch($stmt->execute());
                $result=$stmt->rowCount();//
                break;
        }


        if(self::$debug){ tsTool::debugMsg(__METHOD__,$queryGroup[1],'return'); }//调试用

        return $result;
    }
    protected function forBindParam($kval=null,$stmt=null){
        foreach($kval as $k=>$v){

            $bindParam='$stmt->bindParam(\':'.$k.'\',$'.$k.');';
            if(self::$debug){ tsTool::debugMsg(__METHOD__,$bindParam,'row'); }//调试
            eval($bindParam);

            $arr='$'.$k.'=\''.$v.'\';';
            if(self::$debug){  tsTool::debugMsg(__METHOD__,$arr,'row'); }//调试
            eval($arr);

        }
    }
    protected function forSignArr($kval=null,$type=null){
        foreach($kval as $k=>$v){
            preg_match("/^".$type."/i",$k,$a);
            if( !empty($a[0]) ){

                $param='$'.$k.'=\''.$v.'\';';
                if(self::$debug){ tsTool::debugMsg(__METHOD__,$param,'row'); }//调试
                eval($param);

                $arr='$array[\':'.$k.'\']=$'.$k.';';
                if(self::$debug){ tsTool::debugMsg(__METHOD__,$arr,'row'); }//调试
                eval($arr);

            }
        }
        //var_dump($array);exit;//
        return $array;
    }

    protected function sqlResult($stmt,$kval=null,$dataType=null,$num=null){
        $result='';
        switch($dataType){
            case null:
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'class':

                /*$table=$this->signMade(microtime());
                $dataClass=$this->classStr($kval,$table);
                eval($dataClass);*/

                $result=$stmt->fetchAll(PDO::FETCH_CLASS,'stdClass');
                break;
            case 'func':

                $func=$this->signMade(microtime());
                $dataFunc=$this->funcStr($kval,$func);
                eval($dataFunc);

                $result=$stmt->fetchAll(PDO::FETCH_FUNC,$func);
                break;
            case 'col':
                if($num==null){$num=0;}
                $result=$stmt->fetchAll(PDO::FETCH_COLUMN,$num);
                break;
            case 'row':
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                break;
            default:break;
        }

        //print_r( $result );
        return $result;

    }

    protected function classStr($kval=null,$className=null){

        $keyStr='';$n=0;
        //var_dump($kval);
        foreach($kval as $k=>$v){
            $n++;
            $k=preg_replace('/whe_/i','',$k);
            if(count($kval)==$n){
                $keyStr.='public $'.$k.';';
            }else{
                $keyStr.='public $'.$k.'; ';
            }
        }

        $dataClass="class ".$className."{ $keyStr } ";

        //var_dump($dataClass);//exit;

        return $dataClass;
    }

    protected function funcStr($kval=null,$funcName=null){

        $keyStr='';$valStr='';$n=1;

        //var_dump($kval);
        foreach($kval as $k=>$v){
            $k=preg_replace('/whe_/i','',$k);
            if(count($kval)==$n){
                $keyStr.='$'.$k; $valStr.='{$'.$k.'}';
            }else{
                $keyStr.='$'.$k.','; $valStr.='{$'.$k.'}:'; $n++;
            }
        }

        $dataFunc='function '.$funcName.'('.$keyStr.'){ return "'.$valStr.'"; }';
        //$dataFunc="function test_vcode(\$uid,\$vcode){ return \"{\$uid}:{\$vcode}\"; }";

        return $dataFunc;
    }

    protected function insertGroup($sql=null){

        $insMatch=$this->insMatch($sql);
        $insArr=$insMatch[0];
        $sql=$insMatch[1];

        //

        $array[0]=$insArr;
        $array[1]=$sql;

        return $array;
    }

    protected function insMatch($sql=null){

        preg_match("/INSERT.*\((.*)\).*VALUES\(/",$sql,$arr);
        $keys=explode(',', $this->triMall($arr[1]) );//var_dump($keys);//
        preg_match("/VALUES\((.*)\)/",$sql,$arr);
        $values=explode(',', preg_replace('/\'/i','',$arr[1] ) );//print_r($values);//

        $kval =  array_combine($keys,$values);
        //var_dump($kval);//

        foreach($kval as $k=>$v){ $kval['ins_'.$k]=$v; unset($kval[$k]);}
        //var_dump($kval);//

        $array[0]=$kval;

        $valStr='';$n='0';
        foreach($kval as $k=>$v){
            $n++;
            if(count($values)==$n){ $valStr.=':'.$k;}
            else{ $valStr.=':'.$k.','; }
        }
        $sql=preg_replace("/^(.*VALUES\()(.*)(\).*)$/",'$1'.$valStr.'$3',$sql);

        $array[1]=$sql;

        //var_dump($array);exit;//

        return $array;
    }

    protected function updateGroup($sql=null){

        $upSetMatch=$this->upSetMatch($sql); //var_dump($upSetMatch);
        $setArr=$upSetMatch[0];
        $sql=$upSetMatch[1];

/*        $whereMatch=$this->whereMatch($sql); //var_dump($whereMatch);
        $wheArr=$whereMatch[0];
        $sql=$whereMatch[1];
        $array[0]=array_merge($setArr,$wheArr);*/

        $array[0]=$setArr;
        $array[1]=$sql;

        //var_dump($array);//exit;

        return $array;
    }

    protected function upSetMatch($sql=null){

        $setValueStyle='[\w+(\s+|)\=(\s+|)\'(\s+|)\w+(\s+|)\'|\w+(\s+|)\=(\s+|)\'(\s+|)\w+(\s+|)\'(\s+|)\,(\s+|)]+';
        preg_match("/.*SET[\s+|](".$setValueStyle.")[\s+|]WHERE.*$/i",$sql,$arr);
        //var_dump($arr);//exit;//

        preg_match_all("/(\w+)\=\'(\w+)\'+|(\w+)\=\'(\w+)\'\,+/i",$this->triMall($arr[1]),$arr);
        //var_dump($arr);//exit;//

        $setArr =  array_combine($arr[1],$arr[2]);
        foreach($setArr as $k=>$v){ $setArr['set_'.$k]=$v; unset($setArr[$k]);}
        //var_dump($setArr);//exit;//
        $array[0]=$setArr;

        $setStr='';$n='0';
        foreach($setArr as $k=>$v){
            $n++;
            if(count($setArr)==$n){ $setStr.=''.preg_replace("/(set_)/",'',$k).'=:'.$k.'';}
            else{ $setStr.=''.preg_replace("/(set_)/",'',$k).'=:'.$k.','; }
        }
        //var_dump($setStr);//exit;//

        $sql=preg_replace("/(.*SET).*(WHERE.*)$/i",'$1 '.$setStr.' $2',$sql);
        //var_dump($sql);exit;//

        $array[1]=$sql;

        //var_dump($array);exit;//
        return $array;
    }


    protected function selectGroup($sql=null){

        $selMatch=$this->selMatch($sql);
        $selArr=$selMatch[0];
        $sql=$selMatch[1];
        $array[0]=$selArr;

        if( $this->marchKeyWord($sql,'WHERE')&&!$this->marchKeyWord($sql,'GROUP') ){
            //var_dump($sql);exit;

            $whereMatch=$this->whereMatch($sql); //var_dump($whereMatch);
            $wheArr=$whereMatch[0];
            $sql=$whereMatch[1];
            //$array[0]=array_merge($selArr,$wheArr);
            $array[0]=$wheArr;
        }

        $array[1]=$sql;

        //var_dump($array);exit;//
        return $array;
    }


    protected function selMatch($sql=null){

        if( $this->marchKeyWord($sql,'*') ){
            $array[0]='';
            $selStr='*';
        }
        else{
            preg_match("/SELECT[\s+](.*)[\s+]FROM.*$/i",$sql,$arr);
            //var_dump($arr[1]);//exit;
            $numArr=preg_replace("/'/",'',$this->triMall($arr[1]));
            $array[0]='';
            $selStr=$this->filterValue($numArr);
        }

        $sql=preg_replace("/(SELECT[\s+]).*([\s+]FROM.*)$/i",'$1'.$selStr.'$2',$sql);
        //var_dump($sql);exit;//

        $array[1]=$sql;

        //var_dump($array);exit;//
        return $array;

    }


    protected function deleteGroup($sql=null){

        $whereMatch= $this->whereMatch($sql);
        $whereArr=$whereMatch[0];
        $sql=$whereMatch[1];
        $array[0]=$whereArr;

        if($this->marchKeyWord($sql,'LIMIT')){
            $limitMatch= $this->limitMatch($sql);
            $limArr=$limitMatch[0];
            $sql=$limitMatch[1];
            $array[0]=array_merge($whereArr);
        }

        $array[1]=$sql;

        //var_dump($array);exit;

        return $array;

    }

    protected function whereMatch($sql=null){

        $whereValueStyle='[\s??\S+\s??\=\s??\S+\s?? | \s??\S+\s??\=\s??\S+\s??AND]+';

        if( $this->marchKeyWord($sql,'ORDER') ){
            preg_match("/.*WHERE[\s??](".$whereValueStyle.")[\s??][ORDER|].*$/i",$sql,$arr);
        }
        else{
            preg_match("/.*WHERE[\s??](".$whereValueStyle.")$/i",$sql,$arr);
            //var_dump($arr);//exit;//
        }

        //var_dump($arr);exit;//

        $repStr=preg_replace("/`/i",'',$arr[1] );
        //var_dump($repStr);exit;
        preg_match_all("/(\S+)[\=|(LIKE)]\'(\S+)\'/i",$repStr,$where);
        //var_dump($where);exit;//
        $whereArr =  array_combine($where[1],$where[2]);
        foreach($whereArr as $k=>$v){
            $kk=preg_replace("/\./i","_",$k);//转换联表查询的 点号
            $whereArr['whe_'.$kk]=$v;
            unset($whereArr[$k]);
        }
        //var_dump($whereArr);exit;//
        $array[0]=$whereArr;


        $wheStr='';$n='0';
        foreach($whereArr as $k=>$v){
            $n++;
            $kk=preg_replace("/(whe_)/i",'',$k);
            $kk=preg_replace("/_/i",'.',$kk);//联表查询的 点号 转换
            $kk=preg_replace("/^(\w+\.)(\w+)$/i",'$1`$2`',$kk);//联表查询的 字段 加引号
            if(count($whereArr)==$n){ $wheStr.=' '.$kk.'=:'.$k.' ';}
            else{ $wheStr.=' '.$kk.'=:'.$k.' AND '; }
        }
        //var_dump($wheStr);exit;

        if( $this->marchKeyWord($sql,'ORDER') ){
            $sql=preg_replace("/(.*WHERE).*(ORDER.*)$/i",'$1'.$wheStr.'$2',$sql);
            //var_dump($sql);exit;//
        }
        else{
            $sql=preg_replace("/(.*WHERE).*$/i",'$1 '.$wheStr,$sql);
            //var_dump($sql);exit;//
        }

        $array[1]=$sql;


        //var_dump($array);exit;//
        return $array;

    }

    protected function limitMatch($sql=null){
        $limValueStyle='[0-9]+|[0-9]+\,[0-9]+';
        preg_match("/.*LIMIT[\'|\s]+(".$limValueStyle.")[\'|\s|]+$/i",$sql,$arr);
        //var_dump($arr);//exit;//

        $limStr=$this->filterValue( $this->triMall($arr[1]) );
        //var_dump($limStr);//exit;//

        $array[0]='';

        //echo $sql;

        $sql=preg_replace("/(.*LIMIT[\s+])[\'|\s]+.*[\'|\s]+/i","$1$2 ".$limStr."",$sql);
        //var_dump($sql);exit;//

        $array[1]=$sql;

        //var_dump($array);exit;
        return $array;
    }

    protected function tableMatch($sql=null,$repPREFIX=null){

        $arr='';
        if( $this->marchKeyWord($sql,'WHERE') ){
            preg_match("/FROM[\s+](.*)[\s+]WHERE/",$sql,$arr);
        }elseif( $this->marchKeyWord($sql,'ORDER') ){
            preg_match("/FROM[\s+](.*)[\s+]ORDER/",$sql,$arr);
        }

        $arr[1]=preg_replace('/`/i','',$arr[1]);

        if( !empty($repPREFIX) ){  $arr[1]=preg_replace('/'.self::$DB->PREFIX.'/i','',$arr[1]); }
        //var_dump($arr);//exit;//

        return $arr[1];

    }


    protected function marchKeyWord($sql=null,$word=null){

        preg_match_all("/\*|JOIN|WHERE|GROUP|ORDER|LIMIT/i",$sql,$Arr);

        //var_dump($Arr);

        if( !empty($Arr[0]) ){

            foreach($Arr[0] as $k=>$v){
                if($v==$word){
                    return true;
                }
            }

        }
        return false;
    }

    protected function tryCatch($func=null){

        try {
            eval($func.';');
        }
        catch(PDOException $e)
        {

            print $e->getMessage();
            $this->rollBack();

            exit;
        };

    }

    protected function filterValue($str)
    {

        $str = str_replace("AND","",$str);
        $str = str_replace("EXECUTE","",$str);
        $str = str_replace("UPDATE","",$str);
        $str = str_replace("COUNT","",$str);
        $str = str_replace("CHR","",$str);
        $str = str_replace("MID","",$str);
        $str = str_replace("MASTER","",$str);
        $str = str_replace("TRUNCATE","",$str);
        $str = str_replace("CHAR","",$str);
        $str = str_replace("DECLARE","",$str);
        $str = str_replace("SELECT","",$str);
        $str = str_replace("CREATE","",$str);
        $str = str_replace("DELETE","",$str);
        $str = str_replace("INSERT","",$str);
        $str = str_replace("and","",$str);
        //
        $str = str_replace("execute","",$str);
        $str = str_replace("update","",$str);
        $str = str_replace("count","",$str);
        $str = str_replace("chr","",$str);
        $str = str_replace("mid","",$str);
        $str = str_replace("master","",$str);
        $str = str_replace("truncate","",$str);
        $str = str_replace("char","",$str);
        $str = str_replace("declare","",$str);
        $str = str_replace("select","",$str);
        $str = str_replace("create","",$str);
        $str = str_replace("delete","",$str);
        $str = str_replace("insert","",$str);
        $str = str_replace("'","",$str);
        $str = str_replace("\"","",$str);
        $str = str_replace(" ","",$str);
        $str = str_replace("or","",$str);
        $str = str_replace("=","",$str);
        $str = str_replace("%20","",$str);
        //echo $str;
        return $str;
    }

    protected function triMall($str)//删除空格
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        return str_replace($qian,$hou,$str);
    }

    protected function signMade($str=null){

        if(empty($str)){ $str=microtime(); };

        $sign=substr(md5($str),'0','8');//var_dump($sign);//
        $sign=mb_strtoupper($sign);

        $sign=str_replace('0','c', $sign );
        $sign=str_replace('1','d', $sign );
        $sign=str_replace('2','e', $sign );
        $sign=str_replace('3','f', $sign );
        $sign=str_replace('4','g', $sign );
        $sign=str_replace('5','h', $sign );
        $sign=str_replace('6','i', $sign );
        $sign=str_replace('7','j', $sign );
        $sign=str_replace('8','k', $sign );
        $sign=str_replace('9','l', $sign );

        return $sign;
    }



} 