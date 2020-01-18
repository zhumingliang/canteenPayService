<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/8
 * Time: 17:20
 */

namespace app\merchantreceive\controller\contract;
use app\beans\Body;
use app\beans\Message;
use app\beans\contract\AgentSignNotifyResponseHead;
use app\beans\contract\AgentSignNotifyResponseInfo;
use app\sign\SignatureAndVerification;
use app\utils\TimeUtils;
use think\Controller;
use think\Response;
use app\utils\HttpClientUtils;
use think\Log;

class AgentSignNotify extends Controller
{
    public function sign()
    {
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
        if ('1' !== $result)
        {
            $responseStr = '验签失败！';
            Log::info("AgentSignNotify验签结果：".$responseStr);
        }
        else
        {
            $respHead = new AgentSignNotifyResponseHead();
            $respInfo = new AgentSignNotifyResponseInfo();
            //head信息
            $respHead->setTransSeqNum($requestBodyOfDecoded->message->head->transSeqNum);
            $respHead->setTransCode($requestBodyOfDecoded->message->head->transCode);

            $respHead->setTransFlag("01");
            $respHead->setTimeStamp(TimeUtils::getTimeStamp('YmdHisu'));
            $respHead->setReturnCode("success");
            $respHead->setReturnMessage("接收签约通知成功");

            //Info信息
            $respInfo->setEpayCode($requestBodyOfDecoded->message->info->epayCode);
            $respInfo->setMerchantId($requestBodyOfDecoded->message->info->merchantId);
            $respInfo->setOldStatus("1");

//            $respMessage = new Message($respInfo,$respHead);
//            $responseBody = new Body($requestBodyOfDecoded->format,$respMessage);
//            $responseJson = json_encode($responseBody);


            $respMessage = new Message($respInfo,$respHead,$requestBodyOfDecoded->message->info,$requestBodyOfDecoded->message->head);
            $responseBody = new Body($requestBodyOfDecoded->format,$respMessage);
            $responseJson = json_encode($responseBody,JSON_UNESCAPED_UNICODE);
            // 加签名
            $signatrue = SignatureAndVerification::sign_with_sha1_with_rsa($responseJson);
            $responseStr = $signatrue."||".(base64_encode($responseJson));
        }
        Log::info("AgentSignNotify向后台发送的报文加密前为：".$responseJson);
        Log::info("AgentSignNotify向后台发送的报文加密后为：".$responseStr);
        //将加签名之后的报文发送给浏览器
        $httpresp = new Response();
        $httpresp->contentType('text/plain','utf-8');
        $httpresp->data($responseStr);
        $httpresp->send();

    }
}