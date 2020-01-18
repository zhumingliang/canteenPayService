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
use app\sign\SignatureAndVerification;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use think\Controller;
use think\Response;
use think\Log;

class QueryBill extends Controller
{
    public function index(){
        dump('===============');
    }
    /**
     * 账单查询接口(金额规则为0的)getDirectJoinMerchBill
     * 金额规则为0，欠费账单支付，返回单个子账单
     *
     */
    public function getBill(){

        $util = new HttpClientUtils();
        $requestMap = $util->requestBodyOfBase64();

        //使用base64解析完成后的requestBody
        $requestBodyOfDecoded = $requestMap['requestBodyOfDecoded'];
        //解析前的requestBody
        $requestBody = $requestMap['requestBody'];
        //获取缴费中心传送过来的签名
        $signatureString = $requestMap['signatureString'];

        $result = (string)SignatureAndVerification::read_cer_and_verify_sign($requestBody,$signatureString);
        Log::info("获取到的报文验签的结果为：".$result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        }else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();

            //      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : null);
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : null);
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : null);
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : null);
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : null);
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : null);
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : null);
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : null);
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('0');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

            //      封装info内部类bill内部的descDtail
            $descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "0.01元");
            $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1, $descDtail2, $descDtail3, $descDtail4, $descDtail5, $descDtail6);
            //      封装info内部类bill内部的unitDetail
            $unitDetail = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            //      封装info内部类bill，并将其转化为array形式封装给info
            $respBill->setUnitDetail($unitDetail);
            $respBill->setOweAmt("0.01");

            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");
            $respBill->setDescDetails($respDescDetail);
            $respBills = array($respBill);

            $respInfo->setTotalBillCount('1');
            $respInfo->setBills($respBills);

            //       封装响应的消息头信息
            $respHead->setTransSeqNum($requestBodyOfDecoded->message->head->transSeqNum);
            $respHead->setTransCode($requestBodyOfDecoded->message->head->transCode);
            $respHead->setChannel($requestBodyOfDecoded->message->head->channel);

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setReturnCode("0000");
            $respHead->setReturnMessage("账单查询成功，返回成功标志");

            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);;
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));


            Log::info("向后台发送的报文加密前为：" . $responseJson);
        }
        Log::info("向后台发送的报文加密后为：".$responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain','utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();

    }
}