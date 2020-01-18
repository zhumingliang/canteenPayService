<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/4/15
 * Time: 18:16
 */

namespace app\merchantsend\controller\contract;

use think\Config;
use think\Controller;

use app\beans\Message;
use app\beans\contract\AgentQueryByCodEpayRequestHead;
use app\beans\contract\AgentQueryByCodEpayRequestInfo;
use app\beans\Body;
use app\utils\HttpClientUtils;
use app\utils\TimeUtils;
use app\utils\ObjectUtils;

class AgentQueryByCodEpay extends Controller
{
    public function index()
    {

        $config = config::get('merchantconfig.agentQueryByCodEpay');
        $url =$config['url'];
        $transCode = $config['transcode'];
        $this->assign(['url'=>$url,'transCode'=>$transCode]);
        return view();
    }

    public  function querybycodepay()
    {
        $config = config::get('merchantconfig.agentQueryByCodEpay');
        $url =$config['url'];
        $transCode = $config['transcode'];

        // 以当前时间为时间戳
        $timestamp = TimeUtils::getTimeStamp('YmdHisu');


        $format = "json";
        $info = new AgentQueryByCodEpayRequestInfo();
        $head = new AgentQueryByCodEpayRequestHead();

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
        // 缴费项目编号
        $info->setEpayCode($_POST['epayCode']);
        // 缴费项目配置的主商户在商E付系统的编号
        $info->setMerchantId($_POST['merchantId']);
        // 查询状态
        $info->setAgentSignStatus($_POST['agentSignStatus']);
        // 页码
        $info->setPageNo($_POST['pageNo']);
        // 页大小
        $info->setPageSize($_POST['pageSize']);

        $message = new Message($info, $head);
        $body = new Body($format, $message);

        //将数据发送到后端进行处理，返回响应结果
        $util = new HttpClientUtils();
        $result = $util->getDataAndDetail($url,$body);
        $result = get_object_vars($result);

        $result = ObjectUtils::object_to_array($result);

        $data['returnCode'] = $result['message']['head']['returnCode'];
        $data['returnMessage'] = $result['message']['head']['returnMessage'];
        $data['timeStamp'] = $result['message']['head']['timeStamp'];
        $data['transCode'] = $transCode;
        $data['transFlag'] = $result['message']['head']['transFlag'];
        $data['transSeqNum'] = "BRIDGE".$timestamp.$_POST['epayCode'];
        $data['epayCode'] = $_POST['epayCode'];
        $data['merchantId'] = $_POST['merchantId'];
        $data['hasNextPage'] = $result['message']['info']['hasNextPage'];
        $data['contractList'] = json_encode( $result['message']['info']['contractList']);
        $data['agentSignStatus'] = $_POST['agentSignStatus'];
        $data['pageNo'] = $_POST['pageNo'];
        $data['pageSize'] = $_POST['pageSize'];

        $this->assign(['data'=>$data]);
        return view();
    }
}