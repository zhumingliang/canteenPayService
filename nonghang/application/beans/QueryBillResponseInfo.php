<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/9
 * Time: 10:43
 */

namespace app\beans;


class QueryBillResponseInfo
{
    /** 缴费项目唯一标识号*/
    var $epayCode;
    
        /** 直连商户第三方商户号*/
    var $merchantId;
    
        /** 缴费中心流水号*/
    var $traceNo;
    
        /** 输入要素1*/
    var $input1;
    
        /** 输入要素2*/
    var $input2;
    
        /** 输入要素3*/
    var $input3;
    
        /** 输入要素4*/
    var $input4;
    
        /** 输入要素5*/
    var $input5;

    /** 户主名称*/
    var $custName;
    
        /** 户主地址*/
    var $custAddress;
    
        /** 缓存域信息*/
    var $cacheMem;
    
        /** 备注字段*/
    var $remark;
    
        /** 缴费金额计算规则*/
    var $amtRule;
    
        /** 子账单数量*/
    var $totalBillCount;

    /** 跳转商户地址*/
    var $callBackUrl;

    /** 跳转商户地址超链接提示*/
    var $callBackText;

        /** 账单信息体*/
    var $bills;


    /**
     * @return mixed
     */
    public function getCallBackUrl()
    {
        return $this->callBackUrl;
    }

    /**
     * @param mixed $callBackUrl
     */
    public function setCallBackUrl($callBackUrl)
    {
        $this->callBackUrl = $callBackUrl;
    }

    /**
     * @return mixed
     */
    public function getCallBackText()
    {
        return $this->callBackText;
    }

    /**
     * @param mixed $callBackText
     */
    public function setCallBackText($callBackText)
    {
        $this->callBackText = $callBackText;
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
    public function getCustName()
    {
        return $this->custName;
    }

    /**
     * @param mixed $custName
     */
    public function setCustName($custName)
    {
        $this->custName = $custName;
    }

    /**
     * @return mixed
     */
    public function getCustAddress()
    {
        return $this->custAddress;
    }

    /**
     * @param mixed $custAddress
     */
    public function setCustAddress($custAddress)
    {
        $this->custAddress = $custAddress;
    }

    /**
     * @return mixed
     */
    public function getCacheMem()
    {
        return $this->cacheMem;
    }

    /**
     * @param mixed $cacheMem
     */
    public function setCacheMem($cacheMem)
    {
        $this->cacheMem = $cacheMem;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getAmtRule()
    {
        return $this->amtRule;
    }

    /**
     * @param mixed $amtRule
     */
    public function setAmtRule($amtRule)
    {
        $this->amtRule = $amtRule;
    }

    /**
     * @return mixed
     */
    public function getTotalBillCount()
    {
        return $this->totalBillCount;
    }

    /**
     * @param mixed $totalBillCount
     */
    public function setTotalBillCount($totalBillCount)
    {
        $this->totalBillCount = $totalBillCount;
    }

    /**
     * @return mixed
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param mixed $bills
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    
}