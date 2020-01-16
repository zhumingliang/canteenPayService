<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/1
 * Time: 16:56
 */
namespace app\utils;

class TimeUtils{
    public static function getTimeStamp($format = 'u',$utimestamp=null){
        if (is_null($utimestamp)){
            $utimestamp = microtime(true);
        }

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000);

        return date(preg_replace('`(?<!\\\\)u`',$milliseconds,$format),$timestamp);
    }
}