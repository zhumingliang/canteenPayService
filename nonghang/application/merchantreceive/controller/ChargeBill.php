<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/4/8
 * Time: 17:01
 */

namespace app\merchantreceive\controller;


use app\beans\Body;
use app\beans\ChargeBillResponseHead;
use app\beans\ChargeBillResponseInfo;
use app\beans\Message;
use app\merchantreceive\service\NextMonthPayService;
use app\merchantreceive\service\OrderService;
use app\sign\SignatureAndVerification;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use think\Response;
use think\Log;

class ChargeBill
{
    /**
     * 接收账单缴费（销账）接口getRequest4Sale
     *
     */

    public function getRequest()
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

            $respHead = new ChargeBillResponseHead();
            $respInfo = new ChargeBillResponseInfo();

            /** 销账报文重发次数，通过此字段识别销账报文是否为重发的，0表示首次、1表示重发一次，2表示重发2次，最多重发3次*/
            $resendTimes = isset($requestBodyOfDecoded->message->info->resendTimes) ? $requestBodyOfDecoded->message->info->resendTimes : "";

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setChannel(isset($requestBodyOfDecoded->message->head->channel) ? $requestBodyOfDecoded->message->head->channel : "");
            $respHead->setTransCode(isset($requestBodyOfDecoded->message->head->transCode) ? $requestBodyOfDecoded->message->head->transCode : "");
            $respHead->setTransSeqNum(isset($requestBodyOfDecoded->message->head->transSeqNum) ? $requestBodyOfDecoded->message->head->transSeqNum : "");


            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $traceNo = isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "";
            $respInfo->setTraceNo($traceNo);
            $respInfo->setEpayCode($epayCode);
            /**
             * 返回码为0000时不读取本字段；
             * 返回码非0000时，必须返回本标志位信息。返回true标志自动实时退款，返回false标志不做退款
             *
             * 根据账单请求次数确定返回参数，分为四种情况。
             */
            switch ($resendTimes % 4) {
                case 0;
                    $respHead->setReturnCode("0000");
                    $respHead->setReturnMessage("账单缴费成功");
                    break;
                case 1;
                    $respHead->setReturnCode("1111");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("true");
                    break;
                case 2;
                    $respHead->setReturnCode("1111");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("false");
                    break;
                case 3;
                    $respHead->setReturnCode("JH01");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("");
                    break;
            }

            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);
            //处理订单
            (new OrderService())->orderHandel($requestBodyOfDecoded, $respHead->getReturnCode());
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));

        }
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();

    }


    /**
     * 接收账单缴费（销账）接口(次月缴费)
     *
     */

    public function getPayRequest()
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
        $result = '1';
        if ('1' !== $result) {
            $responseStr = '验签失败！';
        } else {

            $respHead = new ChargeBillResponseHead();
            $respInfo = new ChargeBillResponseInfo();

            /** 销账报文重发次数，通过此字段识别销账报文是否为重发的，0表示首次、1表示重发一次，2表示重发2次，最多重发3次*/
            $resendTimes = isset($requestBodyOfDecoded->message->info->resendTimes) ? $requestBodyOfDecoded->message->info->resendTimes : "";
            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setChannel(isset($requestBodyOfDecoded->message->head->channel) ? $requestBodyOfDecoded->message->head->channel : "");
            $respHead->setTransCode(isset($requestBodyOfDecoded->message->head->transCode) ? $requestBodyOfDecoded->message->head->transCode : "");
            $respHead->setTransSeqNum(isset($requestBodyOfDecoded->message->head->transSeqNum) ? $requestBodyOfDecoded->message->head->transSeqNum : "");

            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $traceNo = isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "";
            $respInfo->setTraceNo($traceNo);
            $respInfo->setEpayCode($epayCode);
            /**
             * 返回码为0000时不读取本字段；
             * 返回码非0000时，必须返回本标志位信息。返回true标志自动实时退款，返回false标志不做退款
             *
             * 根据账单请求次数确定返回参数，分为四种情况。
             */
            switch ($resendTimes % 4) {
                case 0;
                    $respHead->setReturnCode("0000");
                    $respHead->setReturnMessage("账单缴费成功");
                    break;
                case 1;
                    $respHead->setReturnCode("1111");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("true");
                    break;
                case 2;
                    $respHead->setReturnCode("1111");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("false");
                    break;
                case 3;
                    $respHead->setReturnCode("JH01");
                    $respHead->setReturnMessage("账单缴费失败哦");
                    $respInfo->setRefundFlag("");
                    break;
            }
            $respMessage = new Message($respInfo, $respHead, $requestBodyOfDecoded->message->info, $requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format, $respMessage);
            $responseJson = json_encode($responseBody, JSON_UNESCAPED_UNICODE);
            $requestBodyOfDecoded = '';
            //处理缴费
            (new NextMonthPayService())->payHandel($requestBodyOfDecoded, $respHead->getReturnCode());
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue . "||" . (base64_encode($responseJson));

        }
//      将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain', 'utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();

    }


    /**
     * 接收账单缴费（销账失败）接口getRequest4SaleFail   实时退款respInfo.setRefundFlag("true");
     *
     */
    public function getRequestFail()
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

            $respHead = new ChargeBillResponseHead();
            $respInfo = new ChargeBillResponseInfo();

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setChannel(isset($requestBodyOfDecoded->message->head->channel) ? $requestBodyOfDecoded->message->head->channel : "");
            //        $respHead->setChannel("MBNK");
            $respHead->setTransCode(isset($requestBodyOfDecoded->message->head->transCode) ? $requestBodyOfDecoded->message->head->transCode : "");
            //        $respHead->setTransCode("chargeBill");
            $respHead->setTransSeqNum(isset($requestBodyOfDecoded->message->head->transSeqNum) ? $requestBodyOfDecoded->message->head->transSeqNum : "");
            $respHead->setReturnCode("1111");
            $respHead->setReturnMessage("账单缴费失败");

            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $traceNo = isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "";

            $respInfo->setTraceNo($traceNo);
            $respInfo->setEpayCode($epayCode);

            /**
             * 返回码为0000时不读取本字段；
             * 返回码非0000时，必须返回本标志位信息。返回true标志自动实时退款，返回false标志不做退款
             */
            if (!strcmp("0000", $respHead->getReturnCode())) {
                $respInfo->setRefundFlag("true");
            }
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
     * 接收账单缴费（销账失败）接口getRequest4SaleFailFalse   不做退款respInfo.setRefundFlag("false");
     *
     */
    public function getRequestFailFalse()
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
            $respHead = new ChargeBillResponseHead();
            $respInfo = new ChargeBillResponseInfo();

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setChannel(isset($requestBodyOfDecoded->message->head->channel) ? $requestBodyOfDecoded->message->head->channel : "");
            //        $respHead->setChannel("MBNK");
            $respHead->setTransCode(isset($requestBodyOfDecoded->message->head->transCode) ? $requestBodyOfDecoded->message->head->transCode : "");
            //        $respHead->setTransCode("chargeBill");
            $respHead->setTransSeqNum(isset($requestBodyOfDecoded->message->head->transSeqNum) ? $requestBodyOfDecoded->message->head->transSeqNum : "");
            $respHead->setReturnCode("1111");
            $respHead->setReturnMessage("账单缴费失败");

            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $traceNo = isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "";
            $respInfo->setTraceNo($traceNo);
            $respInfo->setEpayCode($epayCode);
            /**
             * 返回码为0000时不读取本字段；
             * 返回码非0000时，必须返回本标志位信息。返回true标志自动实时退款，返回false标志不做退款
             */
            if (!strcmp("0000", $respHead->getReturnCode())) {
                $respInfo->setRefundFlag("false");
            }
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
     * 接收账单缴费（销账失败）接口getRequest4SaleFailNull  返回空报文
     *
     */
    public function getRequestFailNull()
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

            $respHead = new ChargeBillResponseHead();
            $respInfo = new ChargeBillResponseInfo();

            $respHead->setTransFlag("02");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setChannel(isset($requestBodyOfDecoded->message->head->channel) ? $requestBodyOfDecoded->message->head->channel : "");
            //        $respHead->setChannel("MBNK");
            $respHead->setTransCode(isset($requestBodyOfDecoded->message->head->transCode) ? $requestBodyOfDecoded->message->head->transCode : "");
            //        $respHead->setTransCode("chargeBill");
            $respHead->setTransSeqNum(isset($requestBodyOfDecoded->message->head->transSeqNum) ? $requestBodyOfDecoded->message->head->transSeqNum : "");
            $respHead->setReturnCode("1111");
            $respHead->setReturnMessage("账单缴费失败");

            $epayCode = isset($requestBodyOfDecoded->message->info->epayCode) ? $requestBodyOfDecoded->message->info->epayCode : "";
            $traceNo = isset($requestBodyOfDecoded->message->info->traceNo) ? $requestBodyOfDecoded->message->info->traceNo : "";
            $respInfo->setTraceNo($traceNo);
            $respInfo->setEpayCode($epayCode);
            /**
             * 返回码为0000时不读取本字段；
             * 返回码非0000时，必须返回本标志位信息。返回true标志自动实时退款，返回false标志不做退款
             */
            if (!strcmp("0000", $respHead->getReturnCode())) {
                $respInfo->setRefundFlag("false");
            }
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