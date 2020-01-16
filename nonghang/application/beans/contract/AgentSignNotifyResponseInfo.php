<?php
/**
 * Created by PhpStorm.
 * User: KF1181008046
 * Date: 2019/4/8
 * Time: 17:53
 */

namespace app\beans\contract;


class AgentSignNotifyResponseInfo
{
    /** 商户编号 */
    var  $merchantId;
    /** 缴费项目唯一标识号 */
    var  $epayCode;
    /** 第三方商户记录的签约状态 */
    var  $oldStatus;

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
     * @return mixed
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * @param mixed $oldStatus
     */
    public function setOldStatus($oldStatus)
    {
        $this->oldStatus = $oldStatus;
    }
}