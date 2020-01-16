<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/3/27
 * Time: 14:33
 */

namespace app\merchantsend\controller;


use app\beans\Message;
use app\beans\ConfirmTraceRequestHead;
use app\beans\ConfirmTraceRequestInfo;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\ObjectUtils;
use app\utils\TimeUtils;
use think\Config;
use think\Controller;

class ConfirmTrace extends Controller
{
    public function index()
    {
        $confirmtrace = config::get('merchantconfig.confirmtrace');
        $url =$confirmtrace['url'];
        $transCode = $confirmtrace['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);
        return view();
    }

    public function confirm()
    {
        $confirmtrace = config::get('merchantconfig.confirmtrace');
        $url =$confirmtrace['url'];
        $transCode = $confirmtrace['transcode'];

//      封装消息体info
        $merchantId = $_POST['merchantId'];
        $traceNo = $_POST['traceNo'];
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');


        $format = "json";
        $info = new ConfirmTraceRequestInfo();
        $head = new ConfirmTraceRequestHead();

        $info->setMerchantId($merchantId);
        $info->setTraceNo($traceNo);

        $head->setTimeStamp($timestamp);
        $head->setTransCode($transCode);
        $head->setTransFlag('01');
        $head->setTransSeqNum("BRIDGE$timestamp$merchantId");

        $message = new Message($info, $head);
        $body = new Body($format, $message);
//        $body = new RequestBody();
//        $message = new Message();
//        $message->setHead($head);
//        $message->setInfo($info);

//        $body->setFormat($format);
//        $body->setMessage($message);

        /*$requestjson = json_encode($body);
        //       加密，加签名
        $data = SignatureAndVerification::sign_with_sha1_with_rsa($requestjson)."||".base64_encode($requestjson);
        $result = HttpClientUtils::doPostStr($url,$data);
//      处理响应报文返回结果
        $sublength = strripos($result,"||");
        $contentBody =  substr($result,$sublength+2);
        $signature = rtrim($result,"||".$contentBody);
        $yk = SignatureAndVerification::read_cer_and_verify_sign($contentBody,$signature);
        $result = json_decode(base64_decode($contentBody));*/

//      将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url,$body);

        $result = get_object_vars($result);
        $this->assign(ObjectUtils::object_to_array($result));
        return view();
    }
}