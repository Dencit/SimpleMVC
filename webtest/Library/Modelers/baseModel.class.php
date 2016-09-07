<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/6/18  Time: 20:16 */

namespace Modelers;
use Modelers\wpDb;


class baseModel extends wpDb {

    public static $wpDb;

    function __construct(){

        self::$wpDb = new parent(DBUSER,DBPASSWD,DBNAME,DBHOST);

    }

    function selectMade($selectArray=''){
        $s='';
        $select='';
        $selectArrayCount=count($selectArray);

        if($selectArray!=''&& is_array($selectArray)){

            foreach ($selectArray as $k=>$v){
                $s++;
                if( $s==$selectArrayCount ){
                    $select.= $v;
                }else{
                    $select.= $v.',';
                }
            }

            return ' SELECT '.$select;

        }else{
            return ' SELECT * ';
        }

    }


    function tableMade($tableArray=''){

        return ' FROM '.$tableArray;
    }


    function whereMade($whereArray=''){
        $i='';
        $where='';
        $whereArrayCount=count($whereArray);

        if($whereArray==''||$whereArray=='-'){
            return '';
        }

        if(is_array($whereArray) ){

            foreach ($whereArray as $k=>$v){
                $i++;
                if( $i==$whereArrayCount ){
                    $where.= $k.'=\''.$v.'\'';
                }else{
                    $where.= $k.'=\''.$v.'\' AND ';
                }
            }

            return ' WHERE '.$where;

        }

        if(!is_array($whereArray)){
            return ' WHERE '.$whereArray;
        }


    }


    function orderMade($orderArray=''){
        $s='';
        $order='';
        $orderArrayCount=count($orderArray);

        if($orderArray==''){
            return ' ';
        }

        if(is_array($orderArray)){

            foreach ($orderArray as $k=>$v){
                $s++;
                if( $s==$orderArrayCount ){
                    $order.= ''.$v.' ';
                }else{
                    $order.= ''.$v.' ';
                }
            }

            return ' ORDER BY '.$order;

        }

        if(!is_array($orderArray)){
            return ' ORDER BY '.$orderArray.' ';
        }
    }


    function limitMade(){

    }


    function rowSelect($tableArray,$selectArray='',$whereArray='',$orderArray=''){

        $selectMade=$this->selectMade($selectArray);
        $tableMade=$this->tableMade($tableArray);
        $whereMade=$this->whereMade($whereArray);
        $orderMade=$this->orderMade($orderArray);
        $query=$selectMade.$tableMade.$whereMade.$orderMade;

        $wpDb=self::$wpDb;
        $tableRow=$wpDb->get_row( $wpDb->prepare( $query ));
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

        //print_r($query);exit;

        $wpDb=self::$wpDb;
        $tableRow=$wpDb->get_results( $wpDb->prepare( $query ));
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }


    function rowInsert($user,$info){
        $wpDb=self::$wpDb;
        $userInsert=$wpDb->insert($user,$info);
        if($userInsert){
            return true;
        }else{
            return false;
        }
    }

    function rowUpdate($table,$dataArray,$whereArray){
        $wpDb=self::$wpDb;
        $rowUpdate=$wpDb->update($table,$dataArray,$whereArray);
        if($rowUpdate){
            return true;
        }else{
            return false;
        }

    }


////////////////////


//检查式 新增条目
    function rowAddCheck($table,$selectArray,$whereArray,$dataArray,$orderArray=''){

        $rowSelect=$this->rowSelect($table,$selectArray,$whereArray,$orderArray);
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
        if($rowUpdate){
            return $dataArray; }
        else{ return false; }
    }
////加减数据判断,从上~
    private function dataArrayGet($rowSelect,$selectArray,$math='1',$num='1'){
        $dataArray=array();
        if(is_array($selectArray)){

            //print_r($rowSelect);print_r($selectArray);exit;

            foreach($selectArray as $k=>$v){

                $rowSelectV=$rowSelect->$v;

                //print_r($rowSelectV);exit;

                if($math=='1'){
                    $addField=(int)$rowSelectV + (int)$num;
                }else{
                    $addField=(int)$rowSelectV - (int)$num;
                }
                $dataArray[$v]=$addField;
            }
            //print_r($dataArray); exit;
        }else{

            $rowSelectA=$rowSelect->$selectArray;

            if($rowSelectA=='0'){
                $addField='0';
            }else if($math=='1'){
                $addField=(int)$rowSelectA + (int)$num;
            }else{
                $addField=(int)$rowSelectA - (int)$num;
            }
            $dataArray[$selectArray]=$addField;
            //print_r($dataArray); exit;
        }
        return $dataArray;
    }










}