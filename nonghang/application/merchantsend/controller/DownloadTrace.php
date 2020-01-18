<?php
/**
 * Created by PhpStorm.
 * UserService: marui
 * Date: 2019/3/27
 * Time: 14:33
 */
namespace app\merchantsend\controller;


use app\beans\DownloadTraceRequestHead;
use app\beans\DownloadTraceRequestInfo;
use app\beans\Message;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\sign\SignatureAndVerification;
use app\utils\TimeUtils;
use think\Controller;
use think\Config;
class DownloadTrace extends Controller {
    public function index()
    {

        $downloadtrace = config::get('merchantconfig.downloadtrace');
        $url =$downloadtrace['url'];
        $transCode = $downloadtrace['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);

        return view();
    }
    public function download(){
        $downloadtrace = config::get('merchantconfig.downloadtrace');
        $url =$downloadtrace['url'];
        $transCode = $downloadtrace['transcode'];

//      封装消息体info
        $merchantId = $_POST['merchantId'];
        $billDate = $_POST['billDate'];
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new DownloadTraceRequestInfo();
        $head = new DownloadTraceRequestHead();

        $info->setMerchantId($merchantId);
        $info->setBillDate($billDate);
        $info->setBillType("SUCCESS");
        $info->setCharset("UTF-8");
        $info->setFileType("CSV");

        $head->setTimeStamp($timestamp);
        $head->setTransCode($transCode);
        $head->setTransFlag('01');
        $head->setTransSeqNum("BRIDGE".$timestamp.$merchantId);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

        $requestjson = json_encode($body);
        //       加密，加签名
        $data = SignatureAndVerification::sign_with_sha1_with_rsa($requestjson)."||".base64_encode($requestjson);
//        echo "加密后的报文：".$data;
        $util = new HttpClientUtils();
        $util->doPostFile($url,$data);

        return view();
    }
}