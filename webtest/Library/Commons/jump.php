<?php
//跳转
namespace Commons;

class jump{

    function __construct(){



    }

    public static function alertTo($messge = '', $url = 'back')
    {
        //$messge = self::conStr($messge);
        switch ($url) {
            case 'back':
                $url = "javascript:history.go(-1);";
                break;
            case 'index':
                $url = "./";
                break;
            default:
                continue;
        }
        $alert = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            "<script>alert('$messge');location.href='$url';</script>";
        echo $alert;
        exit;
    }

    public static function backTo($messge = '', $url = 'back')
    {
        //$messge = self::conStr($messge);
        switch ($url) {
            case 'back':
                $url = "javascript:history.go(-1);";
                break;
            case 'index':
                $url = "./";
                break;
            default:
                continue;
        }
        $alert = "<script>location.href='$url';</script>";
        echo $alert;
        exit;
    }

    public static function js($messge = '',$url = '-1'){
        //$messge=self::conStr($messge);
        if ('-1' == $url) {
            $url = "javascript:history.go(-1);";
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo "<script>alert('$messge');location.href='$url';</script>";
        exit;
    }

    public static function head($url = ''){
        header("location:$url");
        exit;
    }

}