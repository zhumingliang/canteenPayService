<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 9:13
 */

namespace app\beans;


class Body
{
    /** 格式 */
        var $format;

    /** 消息 */
        var $message;

    /**
     * RequestBody constructor.
     * @param $format
     * @param $message
     */
    public function __construct($format, $message)
    {
        $this->format = $format;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}