<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/2
 * Time: 16:10
 */

namespace app\beans;


class DownloadTraceRequestInfo
{
    /** 对账文件格式*/
    var $fileType;
        /** 编码格式*/
    var $charset;
        /** 编码格式*/
    var $merchantId;
        /** 对账单日期*/
    var $billDate;
        /** 对账单类型*/
    var $billType;
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
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param mixed $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * @return mixed
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    /**
     * @param mixed $billDate
     */
    public function setBillDate($billDate)
    {
        $this->billDate = $billDate;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getBillType()
    {
        return $this->billType;
    }

    /**
     * @param mixed $billType
     */
    public function setBillType($billType)
    {
        $this->billType = $billType;
    }

}