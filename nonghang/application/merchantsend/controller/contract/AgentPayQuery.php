<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/3/27
 * Time: 14:33
 */
namespace app\merchantsend\controller\contract;


use app\beans\contract\AgentPayQueryRequestHead;
use app\beans\contract\AgentPayQueryRequestInfo;
use app\beans\Message;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\ObjectUtils;
use app\utils\TimeUtils;
use think\Controller;
use think\Config;

class AgentPayQuery extends Controller {
    public function index()
    {

        $agentpayquery = config::get('merchantconfig.agentpayquery');
        $url =$agentpayquery['url'];
        $transCode = $agentpayquery['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);
        return view();
    }

    public function payquery(){
        $agentpayquery = config::get('merchantconfig.agentpayquery');
        $url =$agentpayquery['url'];
        $transCode = $agentpayquery['transcode'];

        //      封装消息体info
        $merchantId = $_POST['merchantId'];
        $traceNo = $_POST['traceNo'];
        $billNo = $_POST['billNo'];
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentPayQueryRequestInfo();
        $head = new AgentPayQueryRequestHead();

        $info->setMerchantId($merchantId);
        $info->setTraceNo($traceNo);
        $info->setBillNo($billNo);

        $head->setTimeStamp($timestamp);
        $head->setTransCode($transCode);
        $head->setTransFlag('01');
        $head->setTransSeqNum("BRIDGE$timestamp$merchantId");

        $message = new Message($info, $head);
        $body = new Body($format, $message);
 //      将数据发送到后端进行处理，返回响应结果


        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url,$body);

        $result = get_object_vars($result);
        $this->assign(ObjectUtils::object_to_array($result));

        return $this->fetch();
    }
}