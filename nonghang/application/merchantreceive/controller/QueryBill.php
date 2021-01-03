<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/8
 * Time: 17:01
 */

namespace app\merchantreceive\controller;


use app\beans\Body;
use app\beans\Message;
use app\beans\QueryBillResponseHead;
use app\beans\QueryBillResponseInfo;
use app\merchantreceive\service\NextMonthPayService;
use app\merchantreceive\service\UserService;
use app\sign\SignatureAndVerification;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use think\Controller;
use think\Response;
use think\Log;

class QueryBill extends Controller
{
    public function index()
    {
        dump('===============');
    }

    /**
     * 账单查询接口(金额规则为0的)getDirectJoinMerchBill
     * 金额规则为0，欠费账单支付，返回单个子账单
     *
     */
    public function getBill()
    {
        $util = new HttpClientUtils();
        $requestMap = $util->requestBodyOfBase64();
        //使用base64解析完成后的requestBody
        $requestBodyOfDecoded = $requestMap['requestBodyOfDecoded'];
        //解析前的requestBody
        $requestBody = $requestMap['requestBody'];
        //获取缴费中心传送过来的签名
        $signatureString = $requestMap['signatureString'];
        $result = (string)SignatureAndVerification::read_cer_and_verify_sign($requestBody, $signatureString);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {
            //检测用户是否存在
            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $phone = isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "";
            $username = isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "";
            $check = (new UserService())->checkUser($epayCode, $phone, $username);
            //保存信息
            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();
            if ($check['code']) {
                $respHead->setReturnCode("JH02");
                $respHead->setReturnMessage("未查到您的信息，请核实您的手机号和姓名");
            } else {
                //检测缴费年月是否小于当前月
                $pay_date = isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "";
                $now_date = date("Y-m");
                if (strtotime($pay_date) >= strtotime($now_date)) {
                    $respHead->setReturnCode("JH07");
                    $respHead->setReturnMessage("暂未查到欠费");
                } else {
                    //检测是否在缴费时间
                    $company_id = $check['company_id'];
                    $isPay = (new NextMonthPayService())->isPay($company_id);
                    if ($isPay['code'] == 1) {
                        $respHead->setReturnCode("JH04");
                        $respHead->setReturnMessage("非服务时间");
                    } else {
                        //返回缴费账单
                        $dinnerStatistic = (new NextMonthPayService())->getOrderConsumption($requestBodyOfDecoded, $check['staff_id'], $epayCode, $phone, $check['company_id'], $check['username'], $pay_date);
                        $orderConsumption = $dinnerStatistic['dinnerStatistic'];
                        if (empty($orderConsumption)) {
                            $respHead->setReturnCode("JH07");
                            $respHead->setReturnMessage("暂未查到欠费");
                        } else {
                            $allMoney = 0;
                            $respDescDetail = [];
                            for ($x = 0; $x <= count($orderConsumption); $x++) {
                                if ($x <= count($orderConsumption) - 1) {
                                    $allMoney += $orderConsumption[$x]['order_money'];
                                    //      封装info内部类bill内部的descDtail
                                    $descDtail1 = new QueryBillResponseInfo\DescDetail($orderConsumption[$x]['dinner'] . "消费次数:", $orderConsumption[$x]['order_count']);
                                    $descDtail2 = new QueryBillResponseInfo\DescDetail($orderConsumption[$x]['dinner'] . "消费总额:", abs($orderConsumption[$x]['order_money']));
                                    array_push($respDescDetail, $descDtail1, $descDtail2);
                                }
                            }
                            //      封装info内部类bill，并将其转化为array形式封装给info
                            $respBill->setDescDetails($respDescDetail);
                            $respBill->setOweAmt(abs($allMoney));
                            $respBill->setBillNo($dinnerStatistic['order_num']);
                            $respBill->setBillName("饭堂餐费缴纳");
                            $respBill->setFeeAmt("0.00");
                            $respBills = array($respBill);

                            $respInfo->setTotalBillCount('1');
                            $respInfo->setBills($respBills);

                            $respHead->setReturnCode("0000");
                            $respHead->setReturnMessage("账单查询成功，返回成功标志");
                        }
                    }
                }
            }
            //      封装info信息
            $respInfo->setEpayCode($epayCode);
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : null);
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : null);
            $respInfo->setInput1($phone);
            $respInfo->setInput2($username);
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : null);
            $respInfo->setAmtRule('0');

            //       封装响应的消息头信息
            $respHead->setTransSeqNum($requestBodyOfDecoded->message->head->transSeqNum);
            $respHead->setTransCode($requestBodyOfDecoded->message->head->transCode);
            $respHead->setChannel($requestBodyOfDecoded->message->head->channel);

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));

            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));
        }
        //将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();

    }
}