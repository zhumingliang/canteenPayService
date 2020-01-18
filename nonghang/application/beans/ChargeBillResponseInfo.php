<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/11
 * Time: 14:08
 */

namespace app\beans;


class ChargeBillResponseInfo
{
    /** 缴费项目唯一标识号*/
    var $epayCode;
    
        /** 缴费中心流水号*/
    var $traceNo;
    
        /** 退款标志位*/
    var $refundFlag;

    /**
     * @return mixed
     */
    public function getEpayCode()
    {
        return $this->epayCode;
    }

    /**
     * @param mixed $epayCode
     */
    public function setEpayCode($epayCode)
    {
        $this->epayCode = $epayCode;
    }

    /**
     * @return mixed
     */
    public function getTraceNo()
    {
        return $this->traceNo;
    }

    /**
     * @param mixed $traceNo
     */
    public function setTraceNo($traceNo)
    {
        $this->traceNo = $traceNo;
    }

    /**
     * @return mixed
     */
    public function getRefundFlag()
    {
        return $this->refundFlag;
    }

    /**
     * @param mixed $refundFlag
     */
    public function setRefundFlag($refundFlag)
    {
        $this->refundFlag = $refundFlag;
    }


}