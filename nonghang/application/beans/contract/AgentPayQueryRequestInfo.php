<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/3
 * Time: 9:47
 */

namespace app\beans\contract;


class AgentPayQueryRequestInfo
{
    /** 账单编号 */
    var $billNo;
    /** 商户编号 */
    var $merchantId;
    /** 扣款流水号 */
    var $traceNo;

    /**
     * @return mixed
     */
    public function getBillNo()
    {
        return $this->billNo;
    }

    /**
     * @param mixed $billNo
     */
    public function setBillNo($billNo)
    {
        $this->billNo = $billNo;
    }

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


}