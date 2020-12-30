<?php


namespace app\merchantreceive\model;


use think\Model;

class NextmonthPayT extends Model
{
    protected $resultSetType = 'collection';
    public function dinnerStatistic($pay_date, $company_id, $staff_id)
    {
        $list = self::where('company_id', $company_id)
            ->where('staff_id', $staff_id)
            ->where('pay_date', $pay_date)
            ->where('state', 2)
            ->field('dinner,sum(order_count) as order_count,sum(order_money) as order_money')
            ->group('staff_id,dinner')
            ->orderRaw("field(dinner,'早餐,午餐,晚餐')")
            ->select()->toArray();
        return $list;
    }
}