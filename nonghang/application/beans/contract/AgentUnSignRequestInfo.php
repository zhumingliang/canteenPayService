<?php
/**
 * Created by PhpStorm.
 * User: KF1181008046
 * Date: 2019/4/3
 * Time: 16:11
 */
namespace  app\beans\contract;

class AgentUnSignRequestInfo
{
    /** 商户交易编号 */
    var $orderNo;
    /** 签约编号 */
    var $agentSignNo;
    /** 缴费项目编号 */
    var $epayCode;
    /** 商户编号 */
    var $merchantId;

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @return mixed
     */
    public function getAgentSignNo()
    {
        return $this->agentSignNo;
    }

    /**
     * @param mixed $agentSignNo
     */
    public function setAgentSignNo($agentSignNo)
    {
        $this->agentSignNo = $agentSignNo;
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
    public function getEpayCode()
    {
        return $this->epayCode;
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
    public function getMerchantId()
    {
        return $this->merchantId;
    }
}