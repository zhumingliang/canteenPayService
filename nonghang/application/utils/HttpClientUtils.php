<?php
/**
 * Created by PhpStorm.
 * User: marui
 * Date: 2019/3/27
 * Time: 16:50
 */

namespace app\utils;

use app\sign\SignatureAndVerification;
use think\Controller;
use think\Exception;
use think\Log;

class HttpClientUtils extends Controller
{

    public function getDataAndDetail($url, $body)
    {

        $requestjson = json_encode($body, JSON_UNESCAPED_UNICODE);
        Log::info($url . "向后台发送的报文加密前为：" . $requestjson);
//        echo base64_encode($requestjson);
        //       加密，加签名
        $data = SignatureAndVerification::sign_with_sha1_with_rsa($requestjson) . "||" . base64_encode($requestjson);
        Log::info($url . "向后台发送的报文加密后为：" . $data);
//        echo $data."<br>";

        try {
            $result = $this->doPostStr($url, $data);
            Log::info($url . "后台获取的报文解密前为：" . $result);
//      处理响应报文返回结果
            $resultArr = explode('||', $result);

            $signature = $resultArr[0];
            $contentBody = $resultArr[1];
        } catch (Exception $ex) {
            $this->error("向服务器获取请求报文失败！");
            Log::info($url . "向服务器获取请求报文失败！");
        }
//        echo $requestjson;
        $yk = SignatureAndVerification::read_cer_and_verify_sign($contentBody, $signature);
        Log::info("验签结果".$yk);
        if ('1' !== $yk && !$yk) {
            $this->error("验签失败！");
        }
        $resultJson = base64_decode($contentBody);
        $result = json_decode($resultJson);
        Log::info($url . "后台获取的报文解密后为：" . $resultJson);
        return $result;
    }

    public function doPostStr($url, $data)
    {
//        初始化curl
       /* $curl = curl_init();
//        设置curl的参数
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/x-www-form-urlencoded;charset="utf-8"'));

//        发送post请求
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//        echo $curl."<br>".$url."<br>".$data;
//        采集结果
        $output = curl_exec($curl);
//        echo '<br>'.$output;

//        关闭
        curl_close($curl);
//        返回参数
        return $output;*/
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:text/plain;charset="utf-8"',
                'content' => $data
            )
        );
//      发送请求获取响应结果，一般方法可以在result中直接获取报文
        $content = stream_context_create($options);
        $result = null;
        Log::info($content);
        $result = file((string)$url, 1, $content);
        Log::info($result);
        $output = $result[0];
        return $output;
    }

    public function doPostFile($url, $data)
    {

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded;charset="utf-8"',
                'content' => $data
            )
        );
        Log::info($url . "向后台发送的加密报文为：" . $data);
//      发送请求获取响应结果，一般方法可以在result中直接获取报文
        $content = stream_context_create($options);
        $result = null;
        try {
            $result = file((string)$url, null, $content);
            if (null !== $result) {
                //      读取响应头中的信息获取文件名称
                $filename = null;
                $rsponseInfo = $http_response_header;
                foreach ($rsponseInfo as $value) {
                    if (strpos($value, "filename") !== false) {
                        $sublength = strripos($value, "filename=");
                        $filename = substr($value, $sublength + 9);
                        break;
                    }
                }

                //        读取文件中的文件头信息
                $fileheader = explode(',', $result[0]);
                //        读取报文中的文件信息
                $filedata = array();
                for ($i = 1; $i < count($result); $i++) {
                    $filedata[$i - 1] = explode(",", $result[$i]);

                }
                /* 输入到csv文件，解决乱码问题 */
                $html = "\xEF\xBB\xBF";
                /*输出表头 */
                for ($i = 0; $i < count($fileheader) - 1; $i++) {
                    $html .= $fileheader[$i] . "\t,";
                }
                $html .= $fileheader[count($fileheader) - 1];
                /*输出内容 */
                foreach ($filedata as $value) {
                    for ($i = 0; $i < count($fileheader) && $i < count($value) - 1; $i++) {
                        $html .= $value[$i] . "\t,";
                    }
                    $html .= $value[count($value) - 1];
                }
                /* 输出csv文件 */
                if ($filename !== null) {
                    header("Content-type:text/csv");
                    header("Content-Disposition:attachment;filename=" . $filename);
                    echo $html;
                    exit();
                }
                Log::info("获取到的文件表头信息为：".implode($fileheader));

                /*echo '<br>----------------------日志开始------------------------<br>';
                echo "请求路径为：".$url."<br>";
                echo "请求参数为：".$data."<br>";
                dump("获取到的文件名为：".$filename);
                echo '<hr>';
                echo "<h2>获取到的文件表头信息为：</h2>"."<br>";
                dump($fileheader);
                echo '<hr><hr>';
                echo "<h2>获取到的文件具体内容为：</h2>"."<br>";
                dump($filedata);
                echo '<br>----------------------日志结束------------------------<br>';*/
            }

        } catch (Exception $e) {
            $this->error("文档下载失败！");
        }
    }

    /**
     * 接收报文返回requestBody和使用base64解析后的requestBody以及缴费中心传送的签名
     *
     */
    public function requestBodyOfBase64()
    {
        try {
            $requestContent = \request()->getInput();
            Log::info("后台获取的报文解密前为：" . $requestContent);
//            $requestContent = "k6lECy5TcFx9SNniGM8xg94ZeocFBOIp8xF1wJg817gcBYuN6UHssjwr0/U5W2D1XZIRXJHQkgfluQ2qzZhDl5eiOyHpNgbxR0I/QYxUokaZy3XnSAjCi+uv6O6gti5MCnFs3ZP1l4cKdJrKMPaZowoQKR0aeUUFc3zWTH3LTcg=||eyJmb3JtYXQiOiJqc29uIiwibWVzc2FnZSI6eyJoZWFkIjp7ImJyYW5jaENvZGUiOiIyMTEwIiwiY2hhbm5lbCI6Ik1CTksiLCJ0aW1lU3RhbXAiOiIyMDE4MDkyMTE1MTg0Nzg2NyIsInRyYW5zQ29kZSI6InF1ZXJ5QmlsbCIsInRyYW5zRmxhZyI6IjAxIiwidHJhbnNTZXFOdW0iOiJCUDE4MDkyMTE1MTg1NzM5MDAwOSJ9LCJpbmZvIjp7ImVwYXlDb2RlIjoiSkYtRVBBWTIwMTgwODAyNjU2MDIiLCJpbnB1dDEiOiIxMjM0NTYiLCJtZXJjaGFudElkIjoiMTAzODgxMTA0NDEwMDAxIiwidHJhY2VObyI6IkpGMTgwOTIxMTUxODU3ODQ4NTkyIiwidXNlcklkIjoiMTYzNzUwNDYwMjk5NDM1NiJ9fX0=";
            $resultArr = explode('||', $requestContent);

            $signatureString = $resultArr[0];
            $requestBody = $resultArr[1];
            $requestBodyOfDecoded = base64_decode($requestBody);

            Log::info("后台获取的报文解密后为：" . $requestBodyOfDecoded);

            //使用base64解析完成后的requestBody
            $requestMap['requestBodyOfDecoded'] = json_decode($requestBodyOfDecoded);
            //解析前的requestBody
            $requestMap['requestBody'] = $requestBody;
            //获取缴费中心传送过来的签名
            $requestMap['signatureString'] = $signatureString;
            return $requestMap;

        } catch (Exception $ex) {
            $php_errormsg("向服务器获取请求报文失败！");
        }

    }
}