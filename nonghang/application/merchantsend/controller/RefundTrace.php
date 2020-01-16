<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/3/27
 * Time: 14:33
 */
namespace app\merchantsend\controller;


use app\beans\Message;
use app\beans\RefundTraceRequestHead;
use app\beans\RefundTraceRequestInfo;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\ObjectUtils;
use app\utils\TimeUtils;
use think\Config;
use think\Controller;

class RefundTrace extends Controller {
    public function index()
    {
        $refundtrace = config::get('merchantconfig.refundtrace');
        $url =$refundtrace['url'];
        $transCode = $refundtrace['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);

        return view();
    }

    public function refund(){

        $refundtrace = config::get('merchantconfig.refundtrace');
        $url =$refundtrace['url'];
        $transCode = $refundtrace['transcode'];

//      封装消息体info
        $merchantId = $_POST['merchantId'];
        $amtRefund = $_POST['amtRefund'];
        $traceNo = $_POST['traceNo'];
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new RefundTraceRequestInfo();
        $head = new RefundTraceRequestHead();

        $info->setAmtRefund($amtRefund);
        $info->setMerchantId($merchantId);
        $info->setTraceNo($traceNo);

        $head->setBranchCode('2110');
        $head->setTimeStamp($timestamp);
        $head->setTransCode($transCode);
        $head->setTransFlag('01');
        $head->setTransSeqNum("BRIDGE".$timestamp.$traceNo);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

//      将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url,$body);

        $result = get_object_vars($result);
        $this->assign(ObjectUtils::object_to_array($result));
        return view();
    }
}