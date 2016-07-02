<?php
/* Created by User: soma  Date: 16/6/18  Time: 20:16 */

namespace model;
use \model\wpDb;


class baseModel extends wpDb {

    public $wpDb='';

    function __construct(){

        $this->wpDb = new wpDb(DBUSER,DBPASSWD,DBNAME,DBHOST);

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

        $wpDb=$this->wpDb;
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

        $wpDb=$this->wpDb;
        $tableRow=$wpDb->get_results( $wpDb->prepare( $query ));
        if($tableRow){
            return $tableRow;
        }else{
            return false;
        }

    }


    function rowInsert($user,$info){
        $wpDb=$this->wpDb;
        $userInsert=$wpDb->insert($user,$info);
        if($userInsert){
            return true;
        }else{
            return false;
        }
    }

    function rowUpdate($table,$dataArray,$whereArray){
        $wpDb=$this->wpDb;
        $rowUpdate=$wpDb->update($table,$dataArray,$whereArray);
        if($rowUpdate){
            return true;
        }else{
            return false;
        }

    }







}