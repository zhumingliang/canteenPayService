<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/9
 * Time: 13:48
 * @title 选择套餐循环
 */

namespace app\beans\QueryBillResponseInfo;


class OptionDetail
{
    /**套餐编号 根据编号排序*/
    var $optionCode;
    
        /**套餐名称*/
    var $optionName;
    
        /**套餐金额*/
    var $optionAmt;

    /**
     * OptionDetail constructor.
     * @param $optionCode
     * @param $optionName
     * @param $optionAmt
     */
    public function __construct($optionCode, $optionName, $optionAmt)
    {
        $this->optionCode = $optionCode;
        $this->optionName = $optionName;
        $this->optionAmt = $optionAmt;
    }


}