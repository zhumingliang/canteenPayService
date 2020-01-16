<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/3/27
 * Time: 14:33
 */

namespace app\merchantsend\controller\contract;

use app\beans\contract\AgentPayRequestHead;
use app\beans\contract\AgentPayRequestInfo;
use app\beans\Message;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\ObjectUtils;
use app\utils\TimeUtils;
use think\Config;
use think\Controller;

class AgentPay extends Controller
{
    public function index()
    {
        $agentpay = config::get('merchantconfig.agentpay');
        $url = $agentpay['url'];
        $transCode = $agentpay['transcode'];
        $this->assign(['url' => $url, 'transCode' => $transCode]);
        return view();
    }

    public function pay()
    {
        $agentpay = config::get('merchantconfig.agentpay');
        $url = $agentpay['url'];
        $transCode = $agentpay['transcode'];

        //      封装消息体info
        $merchantId = $_POST['merchantId'];
        $billNo = $_POST['billNo'];
        $agentSignNo = $_POST['agentSignNo'];
        $amount = $_POST['amount'];
        $epayCode = $_POST['epayCode'];
        $input1 = $_POST['input1'];
        $input2 = $_POST['input2'];
        $input3 = $_POST['input3'];
        $input4 = $_POST['input4'];
        $input5 = $_POST['input5'];
        $receiveAccount = $_POST['receiveAccount'];
        $splitAccTemplate = $_POST['splitAccTemplate'];
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentPayRequestInfo();
        $head = new AgentPayRequestHead();

        $info->setMerchantId($merchantId);
        $info->setBillNo($billNo);
        $info->setAgentSignNo($agentSignNo);
        $info->setAmount($amount);
        $info->setEpayCode($epayCode);
        $info->setInput1($input1);
        $info->setInput2($input2);
        $info->setInput3($input3);
        $info->setInput4($input4);
        $info->setInput5($input5);
        $info->setReceiveAccount($receiveAccount);
        $info->setSplitAccTemplate($splitAccTemplate);

        $head->setTimeStamp($timestamp);
        $head->setTransCode($transCode);
        $head->setTransFlag('01');
        $head->setTransSeqNum("BRIDGE" . $timestamp . $epayCode);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

//      将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url, $body);

        $result = get_object_vars($result);
        $this->assign(ObjectUtils::object_to_array($result));

        return $this->fetch();
    }
}