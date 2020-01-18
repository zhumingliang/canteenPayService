<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/8
 * Time: 17:47
 */

namespace app\beans\contract;


class AgentQueryByCodEpayRequestInfo
{
    /** 商户编号 */
    var  $merchantId;
    /** 缴费项目编号 */
    var  $epayCode;
    /** 查询状态 */
    var  $agentSignStatus;
    /** 页码 */
    var  $pageNo;
    /** 页大小 */
    var  $pageSize;

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
    public function getAgentSignStatus()
    {
        return $this->agentSignStatus;
    }

    /**
     * @param mixed $agentSignStatus
     */
    public function setAgentSignStatus($agentSignStatus)
    {
        $this->agentSignStatus = $agentSignStatus;
    }

    /**
     * @return mixed
     */
    public function getPageNo()
    {
        return $this->pageNo;
    }

    /**
     * @param mixed $pageNo
     */
    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }
}