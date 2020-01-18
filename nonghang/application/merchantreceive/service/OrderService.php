<?php


namespace app\merchantreceive\service;


use app\merchantreceive\model\NonghangPayT;
use app\merchantreceive\model\PayT;

class OrderService
{
    public function saveOrder($requestBodyOfDecoded, $staff_id, $epayCode, $phone, $company_id)
    {
        //1.添加记录
        NonghangPayT::create([
            'staff_id' => $staff_id,
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
            'status' => 2,
            'state' => 1,
            'openid' => '',
            'staff_id' => $staff_id,
            'paid_at' => time()
        ]);
        return $orderNum;

    }

}