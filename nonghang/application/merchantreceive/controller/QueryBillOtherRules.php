<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/4/8
 * Time: 17:02
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

class QueryBillOtherRules extends Controller
{

    public function test()
    {
        echo 1;
    }


    /**
     * 账单查询接口(金额规则为2的)getDirectJoinMerchBillRule2
     *
     */
    public function rule2()
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
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {
            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();

//      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : '');
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : '');
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : '');
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : '');
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : '');
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : '');
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : '');
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : '');
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('2');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

//      封装info内部类bill内部的optionDtail
            $optionDtail1 = new QueryBillResponseInfo\OptionDetail("1", "流量购买30M:", "0.01");
            $optionDtail2 = new QueryBillResponseInfo\OptionDetail("2", "流量购买50M:", "0.02");
            $optionDtail3 = new QueryBillResponseInfo\OptionDetail("3", "流量购买100M:", "0.03");
            $optionDtail4 = new QueryBillResponseInfo\OptionDetail("4", "流量购买300M:", "0.04");
            $optionDtail5 = new QueryBillResponseInfo\OptionDetail("5", "流量购买1个G:", "0.05");
            $optionDtail6 = new QueryBillResponseInfo\OptionDetail("6", "流量购买3个G:", "0.06");
            $respOptionDetails = array($optionDtail1, $optionDtail2, $optionDtail3, $optionDtail4, $optionDtail5, $optionDtail6);
            $respBill->setOptionDetails($respOptionDetails);

//      封装info内部类bill内部的descDtail
            /*$descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "0.00元");
            $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1,$descDtail2,$descDtail3,$descDtail4,$descDtail5,$descDtail6);
            $respBill->setDescDetails($respDescDetail);*/

//      封装info内部类bill内部的unitDetail
            $unitDetail = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill->setUnitDetail($unitDetail);
//      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("8.00");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");
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
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));
            Log::info("向后台发送的报文加密前为：" . $responseJson);
        }
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }

    /**
     * 账单查询接口(金额规则为3的)getDirectJoinMerchBillRule3
     *
     */
    public function rule3()
    {
        $util = new HttpClientUtils();
        $requestMap = $util->requestBodyOfBase64();

        //使用base64解析完成后的requestBody
        $requestBodyOfDecoded = $requestMap['requestBodyOfDecoded'];
        //解析前的requestBody
        $requestBody = $requestMap['requestBody'];
        //获取缴费中心传送过来的签名
        $signatureString = $requestMap['signatureString'];
        Log::info("requestBody：" . $requestBody);
        Log::info("signatureString：" . $signatureString);
        $result = (string)SignatureAndVerification::read_cer_and_verify_sign($requestBody, $signatureString);
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        }
        else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();

            //      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "");
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "");
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "");
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "");
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "");
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : "");
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : "");
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('3');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

            //      封装info内部类bill内部的descDtail
            $descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "50.02元");
            $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1, $descDtail2, $descDtail3, $descDtail4, $descDtail5, $descDtail6);
            $respBill->setDescDetails($respDescDetail);

//      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("50.02");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");
            $respBill->setMinAmt("0.00");
//            $respBill->setMaxAmt("0.03");
            $respBill->setBalance("50.00");
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
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }
    // ************************************单账单情景end*****************************************************************************************
    // ************************************多账单情景begin*****************************************************************************************

    /**
     * 账单查询接口(金额规则为0的多账单缴费)
     *getDirectJoinMerch4MultiBill
     *
     */
    public function rule0()
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
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();
            $respBill1 = new QueryBillResponseInfo\Bill();
            $respBill2 = new QueryBillResponseInfo\Bill();

//      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "");
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "");
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "");
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "");
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "");
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : "");
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : "");
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('0');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

//      封装info内部类bill内部的descDtail,多个账单第一个
            $descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "1.00元");
            $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1, $descDtail2, $descDtail3, $descDtail4, $descDtail5, $descDtail6);
            $respBill->setDescDetails($respDescDetail);

            //      封装info内部类bill内部的unitDetail
//            $unitDetail = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
//            $respBill->setUnitDetail($unitDetail);
            //      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("1.00");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");

//      封装info内部类bill内部的descDtail,多个账单第二个
            $descDtail11 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail21 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail31 = new QueryBillResponseInfo\DescDetail("欠费金额:", "2.00元");
            $descDtail41 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail51 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail61 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail1 = array($descDtail11, $descDtail21, $descDtail31, $descDtail41, $descDtail51, $descDtail61);
            $respBill1->setDescDetails($respDescDetail1);
            //      封装info内部类bill内部的unitDetail
            $unitDetail1 = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill1->setUnitDetail($unitDetail1);
            //      封装info内部类bill，并将其转化为array形式封装给info
            $respBill1->setOweAmt("2.00");
            $respBill1->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill1->setBillName("北京市海淀区唐家岭新城T05区2号楼1单元1301");
            $respBill1->setFeeAmt("0.00");
            $respBill1->setExpireDate("20230731");

//      封装info内部类bill内部的descDtail,多个账单第三个
            $descDtail12 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail22 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail32 = new QueryBillResponseInfo\DescDetail("欠费金额:", "2.00元");
            $descDtail42 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail52 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail62 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail2 = array($descDtail12, $descDtail22, $descDtail32, $descDtail42, $descDtail52, $descDtail62);
            $respBill2->setDescDetails($respDescDetail2);
            //      封装info内部类bill内部的unitDetail
            $unitDetail2 = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill2->setUnitDetail($unitDetail2);
            //      封装info内部类bill，并将其转化为array形式封装给info
            $respBill2->setOweAmt("2.00");
            $respBill2->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill2->setBillName("北京市海淀区柳浪家园南里11号楼4单元501");
            $respBill2->setFeeAmt("0.00");
            $respBill2->setExpireDate("20230731");

            $respBills = array($respBill, $respBill1, $respBill2);

            $respInfo->setTotalBillCount('3');
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
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }

    /**
     * 账单查询接口(金额规则为2的随机返回失败错误码)getDirectJoinMerchBill2Fail
     *
     */
    public function rule2F()
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
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();

//      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "");
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "");
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "");
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "");
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "");
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : "");
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : "");
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('2');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

//      封装info内部类bill内部的optionDtail
            $optionDtail1 = new QueryBillResponseInfo\OptionDetail("1", "流量购买30M:", "3.00");
            $optionDtail2 = new QueryBillResponseInfo\OptionDetail("2", "流量购买50M:", "5.00");
            $optionDtail3 = new QueryBillResponseInfo\OptionDetail("3", "流量购买100M:", "10.00");
            $optionDtail4 = new QueryBillResponseInfo\OptionDetail("4", "流量购买300M:", "20.00");
            $optionDtail5 = new QueryBillResponseInfo\OptionDetail("5", "流量购买1个G:", "30.00");
            $optionDtail6 = new QueryBillResponseInfo\OptionDetail("6", "流量购买3个G:", "50.00");
            $respOptionDetails = array($optionDtail1, $optionDtail2, $optionDtail3, $optionDtail4, $optionDtail5, $optionDtail6);
            $respBill->setOptionDetails($respOptionDetails);

//      封装info内部类bill内部的unitDetail
            $unitDetail = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill->setUnitDetail($unitDetail);
//      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("8.00");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");
            $respBills = array($respBill);

            $respInfo->setTotalBillCount('1');
            $respInfo->setBills($respBills);

//       封装响应的消息头信息
            $respHead->setTransSeqNum($requestBodyOfDecoded->message->head->transSeqNum);
            $respHead->setTransCode($requestBodyOfDecoded->message->head->transCode);
            $respHead->setChannel($requestBodyOfDecoded->message->head->channel);
//       随机返回错误码
            $codArr = array("JH01", "JH02", "JH03", "JH04", "JH05", "JH06", "JH07", "JH08", "JH09", "JH10", "JH11", "JH12", "JH13", "JH14", "JH15", "JH16", "JH17");
            $errCod = $codArr[array_rand($codArr, 1)];
            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setReturnCode($errCod);
            $respHead->setReturnMessage("账单查询失败，返回错误码:" . $errCod);

            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);;
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));
            Log::info("向后台发送的报文加密前为：" . $responseJson);
        }
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }

    /**
     * 账单查询接口(金额规则为3的随机返回失败错误码)getDirectJoinMerchBill3Fail
     *
     */
    public function rule3F()
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
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();

//      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "");
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "");
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "");
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "");
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "");
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : "");
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : "");
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('3');

            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

//      封装info内部类bill内部的descDtail
            $descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "50.02元");
            $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年6月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1, $descDtail2, $descDtail3, $descDtail4, $descDtail5, $descDtail6);
            $respBill->setDescDetails($respDescDetail);

//      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("50.02");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");
            $respBill->setMinAmt("0.01");
//            $respBill->setMaxAmt("0.03");
            $respBill->setBalance("50.00");
            $respBills = array($respBill);

            $respInfo->setTotalBillCount('1');
            $respInfo->setBills($respBills);

//       封装响应的消息头信息
            $respHead->setTransSeqNum($requestBodyOfDecoded->message->head->transSeqNum);
            $respHead->setTransCode($requestBodyOfDecoded->message->head->transCode);
            $respHead->setChannel($requestBodyOfDecoded->message->head->channel);
//       随机返回错误码
            $codArr = array("JH01", "JH02", "JH03", "JH04", "JH05", "JH06", "JH07", "JH08", "JH09", "JH10", "JH11", "JH12", "JH13", "JH14", "JH15", "JH16", "JH17");
            $errCod = $codArr[array_rand($codArr, 1)];
            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setReturnCode($errCod);
            $respHead->setReturnMessage("账单查询失败，返回错误码:" . $errCod);

            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);;
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));
            Log::info("向后台发送的报文加密前为：" . $responseJson);
        }
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }

// ************************************多账单情景合并成一个账单begin*****************************************************************************************

    /**
     * 账单查询接口(金额规则为0的多账单合为单账单)getMultipleBillForOne
     *
     */
    public function rule0One()
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
        Log::info("获取到的报文验签的结果为：" . $result);
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {

            $respHead = new QueryBillResponseHead();
            $respInfo = new QueryBillResponseInfo();
            $respBill = new QueryBillResponseInfo\Bill();
            $respBill1 = new QueryBillResponseInfo\Bill();
            $respBill2 = new QueryBillResponseInfo\Bill();

//      封装info信息
            $respInfo->setEpayCode(isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "");
            $respInfo->setMerchantId(isset($requestBodyOfDecoded->message->info->merchantId) ? $requestBodyOfDecoded->message->info->merchantId : "");
            $respInfo->setTraceNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respInfo->setInput1(isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "");
            $respInfo->setInput2(isset($requestBodyOfDecoded->message->info->input2) ? $requestBodyOfDecoded->message->info->input2 : "");
            $respInfo->setInput3(isset($requestBodyOfDecoded->message->info->input3) ? $requestBodyOfDecoded->message->info->input3 : "");
            $respInfo->setInput4(isset($requestBodyOfDecoded->message->info->input4) ? $requestBodyOfDecoded->message->info->input4 : "");
            $respInfo->setInput5(isset($requestBodyOfDecoded->message->info->input5) ? $requestBodyOfDecoded->message->info->input5 : "");
            $respInfo->setCustName("张三丰");
            $respInfo->setCustAddress("北京海淀区温泉凯盛家园1区1号楼2单元999室");
            $respInfo->setCacheMem("0,0.00,S,张三丰,4340152");
            $respInfo->setRemark("备注信息为空");
            $respInfo->setAmtRule('0');
            $respInfo->setCallBackUrl("d3d3LmFiY2hpbmEuY29tL2NuLw==");
            $respInfo->setCallBackText("中国农业银行官网");

//      封装info内部类bill内部的descDtail,多个账单第一个
            $descDtail1 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail2 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail3 = new QueryBillResponseInfo\DescDetail("欠费金额:", "68.00元");
//        $descDtail4 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail5 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail6 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail = array($descDtail1, $descDtail2, $descDtail3, $descDtail5, $descDtail6);
            $respBill->setDescDetails($respDescDetail);

            //      封装info内部类bill内部的unitDetail
            $unitDetail = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill->setUnitDetail($unitDetail);
            //      封装info内部类bill，并将其转化为array形式封装给info

            $respBill->setOweAmt("68.00");
            $respBill->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill->setBillName("凯盛家园电费缴纳");
            $respBill->setFeeAmt("0.00");
            $respBill->setExpireDate("20230731");

//      封装info内部类bill内部的descDtail,多个账单第二个
            $descDtail11 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年7月份");
            $descDtail21 = new QueryBillResponseInfo\DescDetail("供电局编号:", "4340152");
            $descDtail31 = new QueryBillResponseInfo\DescDetail("欠费金额:", "1.00元");
//        $descDtail41 = new QueryBillResponseInfo\DescDetail("缴费月份:", "2018年8月份");
            $descDtail51 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail61 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail1 = array($descDtail11, $descDtail21, $descDtail31, $descDtail51, $descDtail61);
            $respBill1->setDescDetails($respDescDetail1);
            //      封装info内部类bill内部的unitDetail
            $unitDetail1 = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill1->setUnitDetail($unitDetail1);
            //      封装info内部类bill，并将其转化为array形式封装给info
            $respBill1->setOweAmt("1.00");
            $respBill1->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill1->setBillName("北京市海淀区唐家岭新城T05区2号楼1单元1301");
            $respBill1->setFeeAmt("0.00");
            $respBill1->setExpireDate("20230731");

//      封装info内部类bill内部的descDtail,多个账单第三个
            $input1 = isset($requestBodyOfDecoded->message->info->input1) ? $requestBodyOfDecoded->message->info->input1 : "";
            $descDtail12 = new QueryBillResponseInfo\DescDetail("【6月缴费单】", "2018年6月份欠费账单||欠费2元||缴费号码" . $input1 . "||欠缴");
            $descDtail22 = new QueryBillResponseInfo\DescDetail("【7月缴费单】", "2018年7月份欠费账单||欠费1元||缴费号码" . $input1 . "||欠缴");
            $descDtail32 = new QueryBillResponseInfo\DescDetail("【8月缴费单】", "2018年8月份欠费账单||欠费68元||缴费号码" . $input1 . "||欠缴");
            $descDtail42 = new QueryBillResponseInfo\DescDetail("【6、7、8三月欠费单合计】", "6月欠费2,7月欠费1,8月欠费68,总欠费金额1+2+68=71元");
            $descDtail52 = new QueryBillResponseInfo\DescDetail("服务时间:", "每天0:30-23:30期间均可缴费");
            $descDtail62 = new QueryBillResponseInfo\DescDetail("温馨提示:", "北京电力电费代缴，咨询电话：95598 该用户为：预付费用户");
            $respDescDetail2 = array($descDtail12, $descDtail22, $descDtail32, $descDtail42, $descDtail52, $descDtail62);
            $respBill2->setDescDetails($respDescDetail2);
            //      封装info内部类bill内部的unitDetail
            $unitDetail2 = new QueryBillResponseInfo\UnitDetail("unitName", "6.66", "1");
            $respBill2->setUnitDetail($unitDetail2);
            //      封装info内部类bill，并将其转化为array形式封装给info
            $respBill2->setOweAmt("2.00");
            $respBill2->setBillNo(isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "");
            $respBill2->setBillName("2018年6月7月8月三个账单合并支付");
            $respBill2->setFeeAmt("0.00");
            $respBill2->setExpireDate("20230731");

            $respBill2->setOweAmt($respBill->oweAmt + $respBill1->oweAmt + $respBill2->oweAmt);
            $respBills = array($respBill2);

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
        Log::info("向后台发送的报文加密后为：" . $responseStr);
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();
    }

}