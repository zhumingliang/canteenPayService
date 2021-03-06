<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"/Users/apple/www/php/nonghang/public/../application/index/view/index/index.html";i:1555482114;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>jf-DemoOfBridgeForPHP</title>
    <style type="text/css">
        a{
            text-decoration: none;
        }
        a:hover{
            color:red;
        }
        table{
            margin: auto;
        }
        table td{
            /* width:160px; */
            height:30px;
            text-align: center;
        }
        body {
            text-align: center;
        }
    </style>
</head>
<body>
    <br><br><br><br>
    <h1>缴费中心商户直连商户Bridge接入接口DEMO</h1>
    <br>
    <table border="1" cellspacing="0">
        <tr>
            <td style="text-align: center">接口描述</td>
            <td style="text-align: center">接口</td>
            <td style="text-align: center">方法名</td>
        </tr>
        <tr>
            <td><a href='merchantsend\confirm_trace' >缴费单笔流水查询接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeTrans/confirmTrace</td>
            <td>confirmTrace</td>
        </tr>
        <tr>
            <td><a href='merchantsend\refund_trace' >缴费单笔流水退款接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeTrans/refundTrace</td>
            <td>refundTrace</td>
        </tr>

        <tr>
            <td><a href='merchantsend\download_trace'>缴费对账单下载</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/mchtFile/downloadTrace</td>
            <td>downloadTrace</td>
        </tr>
    </table>
    <br><br><br><br>
    <h1>授权缴费签约BRIDGE接入接口DEMO</h1>
    <br>
    <table border="1" cellspacing="0">
        <tr>
            <td style="text-align: center">接口描述</td>
            <td style="text-align: center">接口</td>
            <td style="text-align: center">方法名</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_sign'>授权缴费签约申请接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentSignReq</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_un_sign'>授权缴费解约接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentUnSign</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_sign_query'>授权缴费签约/解约结果查询接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentSignQuery</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_pay'>授权缴费单笔扣款接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentPay</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_pay_query'>授权缴费扣款状态查询接口</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentPayQuery</td>
        </tr>
        <tr>
            <td><a href='merchantsend\contract\agent_query_by_cod_epay'>根据缴费项目编号查询签约</a></td>
            <td>http://10.233.93.55:8888/jf-openapiweb/bridgeContract/contract</td>
            <td>AgentQueryByCodEpay</td>
        </tr>
    </table>
</body>
</html>