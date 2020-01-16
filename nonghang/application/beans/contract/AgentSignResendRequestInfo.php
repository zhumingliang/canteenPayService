<?php
/**
 * Created by PhpStorm.
 * User: KF1181008046
 * Date: 2019/4/3
 * Time: 16:11
 */
namespace  app\beans\contract;

class AgentSignResendRequestInfo
{
    /** 商户交易编号 */
    var $orderNo;
    /** 签约卡号 */
    var $cardNo;
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
     * @param mixed $cardNo
     */
    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
    }

    /**
     * @return mixed
     */
    public function getCardNo()
    {
        return $this->cardNo;
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
     * @return mixed
     */
    public function getInput3()
    {
        return $this->input3;
    }

    /**
     * @param mixed $input2
     */
    public function setInput2($input2)
    {
        $this->input2 = $input2;
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
}