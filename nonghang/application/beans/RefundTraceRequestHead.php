<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 9:21
 */

namespace app\beans;


class RefundTraceRequestHead
{
    /**
     * 缴费中心交易序列号
     * BRIDGE前缀+当前17位时间戳timeStamp+商户号merchantId
     */
    var $transSeqNum;
    
        /**
         * 交易码
         * refundBill
         */
    var $transCode;
        /**
         * 交易上行下送标志
         * 01
         */
    var $transFlag;
    
        /**
         * 时间戳
         * yyyyMMddHHmmssSSS
         */
    var $timeStamp;
    
        /**
         * 分行4位iGoal码，用来前置分行交易
         * 36家分行每个分行分配一个唯一的4位iGoal码
         */
    var $branchCode;

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

    /**
     * @return mixed
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * @param mixed $branchCode
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
    }
}