<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/3
 * Time: 9:47
 */

namespace app\beans\contract;


class AgentPayRequestInfo
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
        /** 账单编号 */
    var $billNo;
        /** 签约编号 */
    var $agentSignNo;
        /** 缴费金额 */
    var $amount;
        /** 收款方账号 */
    var $receiveAccount;
        /** 分账交易模板号 */
    var $splitAccTemplate;
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
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getReceiveAccount()
    {
        return $this->receiveAccount;
    }

    /**
     * @param mixed $receiveAccount
     */
    public function setReceiveAccount($receiveAccount)
    {
        $this->receiveAccount = $receiveAccount;
    }

    /**
     * @return mixed
     */
    public function getSplitAccTemplate()
    {
        return $this->splitAccTemplate;
    }

    /**
     * @param mixed $splitAccTemplate
     */
    public function setSplitAccTemplate($splitAccTemplate)
    {
        $this->splitAccTemplate = $splitAccTemplate;
    }


}