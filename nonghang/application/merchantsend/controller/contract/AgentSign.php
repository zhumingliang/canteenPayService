<?php
/**
 * Created by PhpStorm.
 * User: KF1181008046
 * Date: 2019/3/27
 * Time: 16:07
 */

namespace app\merchantsend\controller\contract;

use app\beans\Body;
use think\Config;
use think\Controller;

use app\beans\Message;
use app\beans\contract\AgentSignReqRequestHead;
use app\beans\contract\AgentSignReqRequestInfo;
use app\beans\contract\AgentSignSubmitRequestInfo;
use app\beans\contract\AgentSignSubmitRequestHead;
use app\beans\contract\AgentSignResendRequestInfo;
use app\beans\contract\AgentSignResendRequestHead;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use app\utils\ObjectUtils;

class AgentSign extends Controller
{

    public function index()
    {
        $agentsign = config::get('merchantconfig.agentsign');
        $url = $agentsign['url'];
        $transCode = $agentsign['transcode'];
        $this->assign(['url' => $url, 'transCode' => $transCode]);
        return view();
    }

    public function submit()
    {
        $agentsign = config::get('merchantconfig.agentsign');
        $url = $agentsign['url'];
        $transCode = $agentsign['transcode'];
        $this->assign(['url' => $url, 'transCode' => $transCode]);
        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');


        $format = "json";
        $info = new AgentSignReqRequestInfo();
        $head = new AgentSignReqRequestHead();

        //head信息
        // 时间戳格式 yyyyMMddHHmmssSSS
        $head->setTimeStamp($timestamp);
        // 交易码，固定
        $head->setTransCode($transCode);
        // 上行下送标志，固定
        $head->setTransFlag('01');
        // 缴费中心交易序列号
        $head->setTransSeqNum("BRIDGE" . $timestamp . $_POST['epayCode']);

        //info信息
        // 缴费项目编号
        $info->setEpayCode($_POST['epayCode']);
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST['merchantId']);
        // 输入要素1
        $info->setInput1($_POST['input1']);
        // 输入要素2
        $info->setInput2($_POST['input2']);
        // 输入要素3
        $info->setInput3($_POST['input3']);
        // 输入要素4
        $info->setInput4($_POST['input4']);
        // 输入要素5
        $info->setInput5($_POST['input5']);
        // 商户交易编号
        $info->setOrderNo($_POST['orderNo']);
        // 客户姓名
        $info->setUserName($_POST['userName']);
        // 证件号
        $info->setCertificateNo($_POST['certificateNo']);
        // 证件类型
        $info->setCertificateType($_POST['certificateType']);
        // 签约卡号
        $info->setCardNo($_POST['cardNo']);
        // 签约卡类型
        $info->setCardType($_POST['cardType']);
        // 手机号码
        $info->setMobileNo($_POST['mobileNo']);
        // 签约有效期
        $info->setInvaidDate($_POST['invaidDate']);
        // 卡片有效期
        $info->setCardDueDate($_POST['cardDueDate']);
        // 卡片CVV2码
        $info->setCVV2($_POST['cVV2']);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

        //将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url, $body);
        $result = get_object_vars($result);

        $agentsignsubmit = config::get('merchantconfig.agentsignsubmit');
        $url = $agentsignsubmit['url'];
        $transCode = $agentsignsubmit['transcode'];
        $result = ObjectUtils::object_to_array($result);

        $data['returnCode'] = $result['message']['head']['returnCode'];
        $data['returnMessage'] = $result['message']['head']['returnMessage'];
        $data['timeStamp'] = $result['message']['head']['timeStamp'];
        $data['transCode'] = $transCode;
        $data['transFlag'] = $result['message']['head']['transFlag'];
        $data['transSeqNum'] = "BRIDGE" . $timestamp . $_POST['epayCode'];
        $data['orderNo'] = $_POST['orderNo'];
        $data['epayCode'] = $_POST['epayCode'];
        $data['merchantId'] = $_POST['merchantId'];
        $data['input1'] = $_POST['input1'];
        $data['input2'] = $_POST['input2'];
        $data['input3'] = $_POST['input3'];
        $data['input4'] = $_POST['input4'];
        $data['input5'] = $_POST['input5'];
        $data['cardNo'] = $_POST['cardNo'];

        $this->assign(['url' => $url, 'transCode' => $transCode, '{cardNo}' => $_POST['cardNo'], 'data' => $data]);
        return view();
    }

    public function submitresult()
    {
        $agentsignsubmit = config::get('merchantconfig.agentsignsubmit');
        $url = $agentsignsubmit['url'];
        $transCode = $agentsignsubmit['transcode'];

        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentSignSubmitRequestInfo();
        $head = new AgentSignSubmitRequestHead();

        //head信息
        // 时间戳格式 yyyyMMddHHmmssSSS
        $head->setTimeStamp($timestamp);
        // 交易码，固定
        $head->setTransCode($transCode);
        // 上行下送标志，固定
        $head->setTransFlag('01');
        // 缴费中心交易序列号
        $head->setTransSeqNum("BRIDGE" . $timestamp . $_POST['epayCode']);

        //info信息
        // 商户交易编号
        $info->setOrderNo($_POST['orderNo']);
        // 验证码
        $info->setVerifyCode($_POST['verifyCode']);
        // 缴费项目编号
        $info->setEpayCode($_POST['epayCode']);
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST['merchantId']);
        // 输入要素1
        $info->setInput1($_POST['input1']);
        // 输入要素2
        $info->setInput2($_POST['input2']);
        // 输入要素3
        $info->setInput3($_POST['input3']);
        // 输入要素4
        $info->setInput4($_POST['input4']);
        // 输入要素5
        $info->setInput5($_POST['input5']);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

        //将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url, $body);

        $result = get_object_vars($result);

        $this->assign(ObjectUtils::object_to_array($result));
        return view();
    }

    public function resend()
    {
        $agentsignresend = config::get('merchantconfig.agentsignresend');
        $url = $agentsignresend['url'];
        $transCode = $agentsignresend['transcode'];

        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');

        $format = "json";
        $info = new AgentSignResendRequestInfo();
        $head = new AgentSignResendRequestHead();

        //head信息
        // 时间戳格式 yyyyMMddHHmmssSSS
        $head->setTimeStamp($timestamp);
        // 交易码，固定
        $head->setTransCode($transCode);
        // 上行下送标志，固定
        $head->setTransFlag('01');
        // 缴费中心交易序列号
        $head->setTransSeqNum("BRIDGE" . $timestamp . $_POST['epayCode']);

        //info信息
        // 商户交易编号
        $info->setOrderNo($_POST['orderNo']);
        // 签约卡号
        $info->setCardNo($_POST["cardNo"]);// "6228450018010601076";// infoTemp.AgentSignSubmitRequestMessage.AgentSignSubmitRequestInfo.CardNo;
        // 缴费项目编号
        $info->setEpayCode($_POST["epayCode"]);
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST["merchantId"]);
        // 输入要素1
        $info->setInput1($_POST["input1"]);
        // 输入要素2
        $info->setInput2($_POST["input2"]);
        // 输入要素3
        $info->setInput3($_POST["input3"]);
        // 输入要素4
        $info->setInput4($_POST["input4"]);
        // 输入要素5
        $info->setInput5($_POST["input5"]);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

        //将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url, $body);

        exit(json_encode($result,JSON_UNESCAPED_UNICODE));
    }

}