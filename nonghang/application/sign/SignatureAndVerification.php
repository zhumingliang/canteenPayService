<?php
/**
 * Created by PhpStorm.
 * UserService: KF1181008046
 * Date: 2019/3/28
 * Time: 10:55
 */

namespace app\sign;

use think\Config;
use think\Log;

class SignatureAndVerification
{
    /*
     * 加签名
     */
    public static function sign_with_sha1_with_rsa($contentForSign)
    {
        $data = base64_encode($contentForSign);
        $certs = array();
        $filePath = 'static/resources/certificate/ailuobo.pfx';
        $keyPass = Config::get('prikey');//'11111111';
        $pkcs12 = file_get_contents($filePath);
        if (openssl_pkcs12_read($pkcs12, $certs, $keyPass)) {
            $privateKey = $certs['pkey'];
//            $publicKey =$certs['cert'];
            $signedMsg = '';
            if (openssl_sign($data, $signedMsg, $privateKey)) {
                $signedMsg = base64_encode(($signedMsg));
                return $signedMsg;
            }
            return '';
        }
        return '';
    }

    /*
     * 验签
     * $contentBody 返回报文中加密json字符串
     * $signature 加签字符串
     * 返回值 1：验签成功
     */
    public static function read_cer_and_verify_sign($contentBody, $signature)
    {
        $filePath = 'static/resources/certificate/TrustPay.cer';
        $certificateCAcerContent = file_get_contents($filePath);
        $certificateCApemContent = '-----BEGIN CERTIFICATE-----' . PHP_EOL
            . chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL)
            . '-----END CERTIFICATE-----' . PHP_EOL;
        $key = openssl_get_publickey($certificateCApemContent);

        $ok = (bool)openssl_verify($contentBody, base64_decode($signature), $key);
        openssl_free_key($key);
        return $ok;
    }
}