<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/1
 * Time: 15:58
 */
namespace  app\beans\contract;

class AgentSignReqRequestInfo
{
    /** 缴费项目编号 */
    var $epayCode;
    /** 商户编号 */
    var $merchantId;
    /** 输入要素1 */
    var $input1;
    /** 输入要素2 */
    var $input2;
    /** 输入要素3 */
    var $input3;
    /** 输入要素4 */
    var $input4;
    /** 输入要素5 */
    var $input5;
    /** 商户交易编号 */
    var $orderNo;
    /** 客户姓名 */
    var $userName;
    /** 证件号 */
    var $certificateNo;
    /** 证件类型 */
    var $certificateType;
    /** 签约卡号 */
    var $cardNo;
    /** 签约卡类型 */
    var $cardType;
    /** 手机号码 */
    var $mobileNo;
    /** 签约有效期 */
    var $invaidDate;
    /** 卡片有效期 */
    var $cardDueDate;
    /** 卡片CVV2码 */
    var $cVV2;

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
    public function getInput1()
    {
        return $this->input1;
    }

    /**
     * @param mixed $input1
     */
    public function setInput1($input1)
    {
        $this->input1 = $input1;
    }

    /**
     * @return mixed
     */
    public function getInput2()
    {
        return $this->input2;
    }

    /**
     * @param mixed $input2
     */
    public function setInput2($input2)
    {
        $this->input2 = $input2;
    }

    /**
     * @return mixed
     */
    public function getInput3()
    {
        return $this->input3;
    }

    /**
     * @param mixed $input3
     */
    public function setInput3($input3)
    {
        $this->input3 = $input3;
    }

    /**
     * @return mixed
     */
    public function getInput4()
    {
        return $this->input4;
    }

    /**
     * @param mixed $input4
     */
    public function setInput4($input4)
    {
        $this->input4 = $input4;
    }

    /**
     * @return mixed
     */
    public function getInput5()
    {
        return $this->input5;
    }

    /**
     * @param mixed $input5
     */
    public function setInput5($input5)
    {
        $this->input5 = $input5;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

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
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getCertificateNo()
    {
        return $this->certificateNo;
    }

    /**
     * @param mixed $certificateNo
     */
    public function setCertificateNo($certificateNo)
    {
        $this->certificateNo = $certificateNo;
    }

    /**
     * @return mixed
     */
    public function getCertificateType()
    {
        return $this->certificateType;
    }

    /**
     * @param mixed $certificateType
     */
    public function setCertificateType($certificateType)
    {
        $this->certificateType = $certificateType;
    }

    /**
     * @return mixed
     */
    public function getCardNo()
    {
        return $this->cardNo;
    }

    /**
     * @param mixed $cardNo
     */
    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param mixed $cardType
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
    }

    /**
     * @return mixed
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }

    /**
     * @param mixed $mobileNo
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;
    }

    /**
     * @return mixed
     */
    public function getInvaidDate()
    {
        return $this->invaidDate;
    }

    /**
     * @param mixed $invaidDate
     */
    public function setInvaidDate($invaidDate)
    {
        $this->invaidDate = $invaidDate;
    }

    /**
     * @return mixed
     */
    public function getCardDueDate()
    {
        return $this->cardDueDate;
    }

    /**
     * @param mixed $cardDueDate
     */
    public function setCardDueDate($cardDueDate)
    {
        $this->cardDueDate = $cardDueDate;
    }

    /**
     * @return mixed
     */
    public function getCVV2()
    {
        return $this->cVV2;
    }

    /**
     * @param mixed $cVV2
     */
    public function setCVV2($cVV2)
    {
        $this->cVV2 = $cVV2;
    }
}
