<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/1
 * Time: 15:58
 */
namespace  app\beans\contract;

class AgentSignReqRequestHead
{
    var $transSeqNum;
    var $transCode;
    var $transFlag;
    var $timeStamp;

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
