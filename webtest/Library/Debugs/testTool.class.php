<?php
/* Created by User: soma Worker: 陈鸿扬 Date: 16/7/9  Time: 23:56 */

namespace Debugs;


class testTool {

    protected static $style;

    protected static $Initial;
    protected static $Peak;
    protected static $cpuResponse;
    protected static $cpuStart;
    protected static $cpuEnd;
    protected static $time;

    function __construct(){

    }

    static function typeLine($text=''){
        echo '<hr/><br/><h2>'.$text.':</h2><br/>';
    }

    static function performs($type=null){

        $result=new \stdClass();

        switch($type){
            default:
                self::$Initial=memory_get_usage()-self::$Initial;
                $result->Initial="内存使用量 ".self::$Initial." bytes" ;
                self::$Peak=memory_get_peak_usage()-self::$Peak;
                $result->Peak="内存使用峰值 ".self::$Peak." bytes" ;
                $data = getrusage();
                self::$cpuResponse=($data['ru_utime.tv_sec'] + $data['ru_utime.tv_usec'] / 1000000);
                $result->response="cpu处理时间 ".self::$cpuResponse;
                break;
            case 'start':
                self::$Initial=memory_get_usage();
                $result->Initial="内存使用量 0 bytes" ;
                self::$Peak=memory_get_peak_usage();
                $result->Peak="内存使用峰值 0 bytes" ;
                $data = getrusage();
                self::$cpuStart=($data['ru_utime.tv_sec'] + $data['ru_utime.tv_usec'] / 1000000);
                $result->start="cpu处理开始时间 0";
                self::$time=self::microtime_float();
                $result->time="进程开始时间 0";
                break;
            case 'end':
                self::$Initial=memory_get_usage()-self::$Initial;
                $result->Initial="内存使用量 ".self::$Initial." bytes" ;
                self::$Peak=memory_get_peak_usage()-self::$Peak;
                $result->Peak="内存使用峰值 ".self::$Peak." bytes" ;
                $data = getrusage();
                self::$cpuEnd=($data['ru_utime.tv_sec'] + $data['ru_utime.tv_usec'] / 1000000)-self::$cpuStart;
                $result->end="cpu处理结束时间 ".self::$cpuEnd;
                $result->time="进程结束时间 ".(self::microtime_float()-self::$time);
                break;
        }

        //print_r($result);//

        return $result;

    }


    static function microtime_float(){

	   list($usec, $sec) = explode(" ", microtime());

	   return ((float)$usec + (float)$sec);

	}


    static function debugMsg($title=null,$content=null,$type=null){

        $css='<style type="text/css">
            .dbm{border:1px;padding: 5px; margin: 10px; word-break: break-all; word-wrap: break-word;}
            .dbm hr{border:0; border-bottom:1px}
            .dbm_hr{border:0; border-bottom:1px dashed #333;}
            .dbm_h3{color:#666;}

            .dbm.row,.dbm.row hr,.dbm.row b{
            color:#888
            }
            .dbm.row,.dbm.row hr{
            border-style:dashed; border-color:#888;
            }

            .dbm.title hr,.dbm.title b{
            color:#666
            }
            .dbm.title,.dbm.title hr{
            border-style:solid; border-color:#666;
            }

            .dbm.start hr,.dbm.start b{
            color:#0200cc
            }
            .dbm.start,.dbm.start hr{
            border-style:solid; border-color:#0200cc;
            }

            .dbm.return hr,.dbm.return b{
            color:#00a917
            }
            .dbm.return,.dbm.return hr{
            border-style:solid; border-color:#00a917;
            }

            .dbm.cut hr,.dbm.cut b{
            color:#cc0000
            }
            .dbm.cut,.dbm.cut hr{
            border-style:solid; border-color:#cc0000;
            }
            </style>';

        if(!self::$style){ echo $css; self::$style=true; };

        switch($type){
            default:
            case'normal':
                echo'<hr class="dbm_hr"/>';
                echo '<h3 class="dbm_h3">[ '.strtoupper($title).' ]</h3>';
                echo'<div class="dbm title">'.
                    '<b>[NORMAL]</b> '.$title.'<hr/>';
                print_r($content);
                echo '</div>';
                echo'<hr class="dbm_hr"/><br/>';
                break;
            case'title':
                echo'<div class="dbm title">'.
                     '<b>[TITLE]</b> '.$title.'<hr/>';
                        print_r($content);
                echo '</div>';
                break;
            case'row':
                echo'<div class="dbm row">'.
                    '<b>[Process]</b> '.$title.'<hr/>';
                        print_r($content);
                echo'</div>';
                break;
            case'return':
                echo '<div class="dbm return">' .
                    '<b>[RETURN]</b> '.$title.'<hr/>';
                        print_r($content);
                echo'</div><hr class="dbm_hr"/><br/>';
                break;
            case'start':
                echo'<hr class="dbm_hr"/>';
                echo '<h3 class="dbm_h3">[ '.strtoupper($title).' ]</h3>';
                echo '<div class="dbm start">' .
                    '<b>[START]</b> '.$title.'<hr/>';
                        print_r($content);
                echo'</div>';
                break;
            case'cut':
                echo '<div class="dbm cut">'.
                    '<b>[CUT] [Process]</b> '.$title.'<hr/>';
                        print_r($content);
                echo'</div>';

                break;
            case 'head':
                echo '<h3 class="dbm_h3">[ '.strtoupper($title).' ] [ '.strtoupper($content).' ]</h3>';
                break;
        }


    }


} 