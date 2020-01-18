<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 9:16
 */

namespace app\beans;


class Message
{
    /** 消息体  */
    var $info;

    /** 消息头部 */
    var $head;

    /**
     * Message constructor.
     * @param $info
     * @param $head
     */
    public function __construct($info, $head, $reqInfo=null,$reqHead=null)
    {
        $this->info = (object)array_merge((array)$reqInfo,(array)$info);
        $this->head = (object)array_merge((array)$reqHead, (array)$head);
    }


}