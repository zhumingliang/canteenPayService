<?php


namespace app\merchantreceive\service;

use app\merchantreceive\model\CompanyStaffT;
use app\merchantreceive\model\NextmonthPaySettingT;
use app\merchantreceive\model\NextmonthPayT;
use app\merchantreceive\model\PayNonghangConfigT;
use app\merchantreceive\model\PayNonghangT;
use app\merchantreceive\model\PayT;
use think\Config;

class NextMonthPayService
{
    public function isPay($company_id)
    {
        $info = NextmonthPaySettingT::where('c_id', $company_id)
            ->where('state', 1)
            ->find();
        $is_pay_day = $info->is_pay_day;
        $dateArr = explode('-', $is_pay_day);
        $begin_pay_date = date("Y-m") . '-' . $dateArr[0];
        $end_pay_date = date("Y-m") . '-' . $dateArr[1];
        $now_date = date("Y-m-d");
        if (strtotime($now_date) >= strtotime($begin_pay_date) && strtotime($now_date) <= strtotime($end_pay_date)) {
            return [
                'code' => 0,
                'msg' => 'success'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail'
            ];
        }
    }

    public function getOrderConsumption($requestBodyOfDecoded, $staff_id, $epayCode, $phone, $company_id, $username, $pay_date)
    {
        //查询缴费记录
        $dinnerStatistic = (new NextmonthPayT())->dinnerStatistic($pay_date, $company_id, $staff_id);
        //添加记录
        PayNonghangT::create([
            'epay_code' => $epayCode,
            'phone' => $phone,
            'trace_no' => isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "",
            'merchant_id' => isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "",
            'user_id' => isset($requestBodyOfDecoded->message->info->userId) ? $requestBodyOfDecoded->message->info->userId : "",
            'content' => json_encode($requestBodyOfDecoded),
            'state' => 2
        ]);
        $orderNum = makeOrderNo();
        //生成支付记录
        $pay = PayT::create([
            'company_id' => $company_id,
            'u_id' => '',
            'money' => 0,
            'order_num' => $orderNum,
            'method_id' => 2,
            'status' => 'paid_fail',
            'type' => 'recharge',
            'state' => 1,
            'openid' => '',
            'staff_id' => $staff_id,
            'paid_at' => time(),
            'username' => $username,
            'phone' => $phone
        ]);
        return [
            'dinnerStatistic' => $dinnerStatistic,
            'order_num' => $orderNum
        ];
    }

    public function payHandel($requestBodyOfDecoded, $code)
    {
        //获取异步通知订单检测状态
        $traceNo = $requestBodyOfDecoded->message->info->traceNo;
        $nonghangOrder = PayNonghangT::where('trace_no', $traceNo)->find();
        if ($nonghangOrder->state == 2 && $code == "0000") {
            $nonghangOrder->state = 1;
        }
        $nonghangOrder->notify_content = json_encode($requestBodyOfDecoded);
        $nonghangOrder->save();
        //修改支付记录订单状态
        if ($code != "0000") {
            return false;
        }
        $company = PayNonghangConfigT::where('code', '=', $nonghangOrder->epay_code)
            ->where('state', 1)->find();
        if (empty($company)) {
            return false;
        }
        //配置支付参数
        Config::set([
            'prikey' => $company->prikey,
            'pfxName' => $company->pfx
        ]);
        //更新次月缴费状态
        $nowdate = date('Y-m-d H:i:s');
        $phone = $requestBodyOfDecoded->message->info->input1;
        $pay_date = $requestBodyOfDecoded->message->info->input3;
        $staff = CompanyStaffT::where('company_id', $company->company_id)
            ->where('phone', $phone)
            ->where('state', 1)
            ->find();
        $staff_id = $staff->id;
        NextmonthPayT::where('staff_id', $staff_id)
            ->where('pay_date', $pay_date)
            ->where('state', 2)
            ->update(['state' => 1, 'pay_method' => 1, 'pay_time' => $nowdate, 'update_time' => $nowdate]);
        //更新订单状态
        $orderNum = $requestBodyOfDecoded->message->info->payBillNo;
        $pay = PayT::where('order_num', $orderNum)->find();
        if ($pay->status == 'paid_fail') {
            $pay->paid_at = time(); // 更新支付时间为当前时间
            $pay->status = 'paid';
            $pay->money = $requestBodyOfDecoded->message->info->payBillAmt;
            $pay->save();
        }
    }
}