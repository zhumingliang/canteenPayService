<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/2
 * Time: 10:07
 */

namespace app\beans;


class ConfirmTraceRequestHead
{
    /**  直连商户请求流水号 */
    var $transSeqNum;
        /**  交易码  */
    var $transCode;
        /**  交易上行下送标志  */
    var $transFlag;
        /** 时间戳  */
    var $timestamp;
    /**
     * @return mixed
     */
    public function getTransSeqNum()
    {
        return $this->transSeqNum;
    }

    /**
     * @param mixed $transSeqNum
     */
    public function setTransSeqNum($transSeqNum)
    {
        $this->transSeqNum = $transSeqNum;
    }

    /**
     * @return mixed
     */
    public function getTransCode()
    {
        return $this->transCode;
    }

    /**
     * @param mixed $transCode
     */
    public function setTransCode($transCode)
    {
        $this->transCode = $transCode;
    }

    /**
     * @return mixed
     */
    public function getTransFlag()
    {
        return $this->transFlag;
    }

    /**
     * @param mixed $transFlag
     */
    public function setTransFlag($transFlag)
    {
        $this->transFlag = $transFlag;
    }

    /**
     * @return mixed
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * @param mixed $timeStamp
     */
    public function setTimeStamp($timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }


}