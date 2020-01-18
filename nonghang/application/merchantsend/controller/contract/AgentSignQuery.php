<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/3/27
 * Time: 16:39
 */

namespace app\merchantsend\controller\contract;

use app\beans\Body;
use app\beans\contract\AgentSignQueryRequestHead;
use app\beans\contract\AgentSignQueryRequestInfo;
use app\beans\Message;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use app\utils\ObjectUtils;
use think\Controller;
use think\Config;

class AgentSignQuery extends Controller
{
    public  function index()
    {
        $agentsignquery = config::get('merchantconfig.agentsignquery');
        $url =$agentsignquery['url'];
        $transCode = $agentsignquery['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);
        return view();
    }

    public function signquery()
    {
        $agentsignquery = config::get('merchantconfig.agentsignquery');
        $url =$agentsignquery['url'];
        $transCode = $agentsignquery['transcode'];

        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentSignQueryRequestInfo();
        $head = new AgentSignQueryRequestHead();

        //head信息
        // 时间戳格式 yyyyMMddHHmmssSSS
        $head->setTimeStamp($timestamp);
        // 交易码，固定
        $head->setTransCode($transCode);
        // 上行下送标志，固定
        $head->setTransFlag('01');
        // 缴费中心交易序列号
        $head->setTransSeqNum("BRIDGE".$timestamp.$_POST['epayCode']);

        //info信息
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setAgentSignNo($_POST['agentSignNo']);
        // 缴费项目编号
        $info->setEpayCode($_POST['epayCode']);
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST['merchantId']);
        // 商户交易编号
        $info->setOrderNo($_POST['orderNo']);


        $message = new Message($info, $head);
        $body = new Body($format, $message);

        //将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url,$body);

        $result = get_object_vars($result);

        $this->assign(ObjectUtils::object_to_array($result));
        return view();
    }

}