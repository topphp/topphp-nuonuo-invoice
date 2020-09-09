<?php
/**
 * 凯拓软件 [临渊羡鱼不如退而结网,凯拓与你一同成长]
 * @package topphp-nuonuo-invoice
 * @date 2020/7/31 10:17
 * @author sleep <sleep@kaituocn.com>
 */
declare(strict_types=1);

namespace Topphp\TopphpNuonuoInvoice;

class Utils
{
    /**
     * 计算签名
     *
     * @param string $path 请求地址
     * @param string $appSecret appSecret
     * @param string $appKey appKey
     * @param string $senId 流水号
     * @param int $nonce 随机码
     * @param string $body 请求包体
     * @param string $timestamp 时间戳
     * @return string 返回签名
     */
    public static function makeSign(
        string $path,
        string $appSecret,
        string $appKey,
        string $senId,
        int $nonce,
        string $body,
        string $timestamp
    ) {
        $pieces  = explode('/', $path);
        $signStr = "a={$pieces[3]
        }&l={$pieces[2]}&p={$pieces[1]}&k={$appKey}&i={$senId}&n={$nonce}&t={$timestamp}&f={$body}";
        return base64_encode(hash_hmac("sha1", $signStr, $appSecret, true));
    }

    /**
     * @param $param
     * @param $errMsg
     * @throws NuonuoException
     * @author sleep
     */
    public static function checkParam($param, $errMsg)
    {
        if (empty($param)) {
            throw new NuonuoException($errMsg);
        }
    }

    /**
     * 以post方式发起http调用
     *
     * @param string $url url
     * @param array|string $params post参数
     * @param array $headers
     * @param int $second url执行超时时间，默认30s
     * @return bool|string
     * @throws NuonuoException
     */
    public static function postCurl(string $url, $params, $headers = [], $second = 100)
    {
        $ch          = curl_init();
        $curlVersion = curl_version();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_error($ch);
            curl_close($ch);
            throw new NuonuoException("curl出错:$error");
        }
    }
}
