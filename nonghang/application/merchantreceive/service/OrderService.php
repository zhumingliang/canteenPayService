<?php


namespace app\merchantreceive\service;


use app\merchantreceive\model\PayNonghangConfigT;
use app\merchantreceive\model\PayNonghangT;
use app\merchantreceive\model\PayT;
use think\Config;

class OrderService
{
    public function saveOrder($requestBodyOfDecoded, $staff_id, $epayCode, $phone, $company_id)
    {
        //1.添加记录
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
            'state' => 1,
            'openid' => '',
            'staff_id' => $staff_id,
            'paid_at' => time()
        ]);
        return $orderNum;

    }

    public function orderHandel($requestBodyOfDecoded, $code)
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
            'pfxName' => $company->pfxName
        ]);


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