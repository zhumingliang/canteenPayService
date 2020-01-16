<?php
/**
 * Created by PhpStorm.
 * User: KF1181008046
 * Date: 2019/3/27
 * Time: 16:42
 */

namespace app\merchantsend\controller\contract;

use think\Config;
use think\Controller;

use app\beans\Message;
use app\beans\contract\AgentUnSignRequestInfo;
use app\beans\contract\AgentUnSignRequestHead;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use app\utils\ObjectUtils;

class AgentUnSign extends Controller
{
    public  function index()
    {
        $agentunsign = config::get('merchantconfig.agentunsign');
        $url =$agentunsign['url'];
        $transCode = $agentunsign['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);
        return view();
    }

    public function agentunsign()
    {
        $agentunsign = config::get('merchantconfig.agentunsign');
        $url =$agentunsign['url'];
        $transCode = $agentunsign['transcode'];

        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentUnSignRequestInfo();
        $head = new AgentUnSignRequestHead();

        //head信息
        // 交易码，固定
        $head->setTransCode($transCode);
        // 上行下送标志，固定
        $head->setTransFlag('01');
        // 缴费中心交易序列号
        $head->setTransSeqNum("BRIDGE".$timestamp.$_POST['epayCode']);
        // 时间戳格式 yyyyMMddHHmmssSSS，
        $head->setTimeStamp($timestamp);

        //info信息
        // 商户交易编号
        $info->setOrderNo($_POST['orderNo']);
        // 签约编号
        $info->setAgentSignNo($_POST['agentSignNo']);
        // 缴费项目编号
        $info->setEpayCode($_POST['epayCode']);;
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST['merchantId']);

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