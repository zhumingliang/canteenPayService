<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:94:"/Users/apple/www/php/nonghang/public/../application/merchantsend/view/confirm_trace/index.html";i:1558595920;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>缴费单笔流水查询</title>
    <style type="text/css">
        table{
            margin: auto;
        }
        table td{
            /* width:160px; */
            height:30px;
            text-align: left;
        }
        body {
            text-align: center;
        }
    </style>
</head>
<body>
    <br><br><br><br><br><br>
    <h2>缴费单笔流水查询</h2>
    <h3>接口:<?php echo $url; ?></h3>
    <h3>交易码:<?php echo $transCode; ?></h3><br>
    <form action='confirm_trace\confirm' method="post">
        <table>
            <tr>
                <td>缴费中心流水号(traceNo)：</td>
                <td><input type="text" name="traceNo" value="" /><font color="red">*必输</font></td>
            </tr>
            <tr>
                <td>商户编号(merchantId)：</td>
                <td><input type="text" name="merchantId" value="" /><font color="red">*必输</font></td>
            </tr>

        </table>
        <input type="submit" value="提交"/>
    </form>
</body>
</html>