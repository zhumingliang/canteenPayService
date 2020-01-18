<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/9
 * Time: 13:49
 * @title 账单详情
 */

namespace app\beans\QueryBillResponseInfo;


class DescDetail
{
    /**账单详情-名称*/
    var $sCpt;
    
        /**账单详情-取值*/
    var $sVal;

    /**
     * DescDetail constructor.
     * @param $sCpt
     * @param $sVal
     */
    public function __construct($sCpt, $sVal)
    {
        $this->sCpt = $sCpt;
        $this->sVal = $sVal;
    }

    /**
     * @return mixed
     */
    public function getSCpt()
    {
        return $this->sCpt;
    }

    /**
     * @param mixed $sCpt
     */
    public function setSCpt($sCpt)
    {
        $this->sCpt = $sCpt;
    }

    /**
     * @return mixed
     */
    public function getSVal()
    {
        return $this->sVal;
    }

    /**
     * @param mixed $sVal
     */
    public function setSVal($sVal)
    {
        $this->sVal = $sVal;
    }



}