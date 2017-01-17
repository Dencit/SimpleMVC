<?php
/* Created by User: soma Worker:陈鸿扬  Date: 16/12/21  Time: 15:24 */

namespace Modelers;

use \Commons\rehomeConfig as con;

class rehomeBase extends RePdoBaseModel {

    protected static $TB;
    protected static $PA;
    protected static $DB;

    function __construct($DB){

        $con= new con();
        self::$TB=$con->data('TABLE');
        self::$PA=$con->data('PATH');
        self::$DB=$con->data('DB');

        parent::__construct($DB);

    }

} 