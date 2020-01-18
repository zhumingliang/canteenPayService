<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 14:24
 */

namespace app\utils;


class ObjectUtils
{

    public static function object_to_array($obj){
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resourse'){
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array'){
                $obj[$k] = (array)self::object_to_array($v);
            }
        }

        return $obj;
    }
}