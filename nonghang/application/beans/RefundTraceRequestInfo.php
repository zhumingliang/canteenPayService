<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 9:05
 */

namespace app\beans;


class RefundTraceRequestInfo
{
    /**
     * 商户编号
     */
    var $merchantId;
    /**
     * 缴费中心流水号
     */
    var $traceNo;
    /**
     * 退款金额
     */
    var $amtRefund;

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
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
    public function getAmtRefund()
    {
        return $this->amtRefund;
    }

    /**
     * @param mixed $amtRefund
     */
    public function setAmtRefund($amtRefund)
    {
        $this->amtRefund = $amtRefund;
    }


}