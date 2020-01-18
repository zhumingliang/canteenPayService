<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/8
 * Time: 17:46
 */

namespace app\beans\contract;


class AgentQueryByCodEpayRequestHead
{
    var  $transCode;
    var  $transFlag;
    var  $transSeqNum;
    var  $timestamp;

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
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}