<?php
/**
 * 凯拓软件 [临渊羡鱼不如退而结网,凯拓与你一同成长]
 * @package topphp-nuonuo-invoice
 * @date 2020/7/31 10:25
 * @author sleep <sleep@kaituocn.com>
 */
declare(strict_types=1);

namespace Topphp\TopphpNuonuoInvoice;

class NuoNuo
{
    /**
     * SDK版本号
     * @var string
     */
    public static $VERSION  = "1.0.4";
    public static $AUTH_URL = "https://open.nuonuo.com/accessToken";

    private static $instance = null;

    private $env       = "sandbox";
    private $apiUrl    = 'https://sandbox.nuonuocs.cn/open/v1/services';
    private $appKey    = ''; // 开放平台appKey
    private $appSecret = ''; // 开放平台appSecret
    private $timeOut   = 6;

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @param string $env
     * @return NuoNuo
     */
    public function setEnv(string $env): self
    {
        $this->env = $env;
        // 正式环境
        if ($env !== 'sandbox') {
            return $this->setApiUrl("https://sdk.nuonuo.com/open/v1/services");
        }
        return $this;
    }

    /**
     * @return mixed
     */
    private function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param mixed $apiUrl
     * @return NuoNuo
     */
    private function setApiUrl($apiUrl): self
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     * @return NuoNuo
     */
    public function setAppKey(string $appKey): self
    {
        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    /**
     * @param string $appSecret
     * @return NuoNuo
     */
    public function setAppSecret(string $appSecret): self
    {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * @param int $timeOut
     * @return NuoNuo
     */
    public function setTimeOut(int $timeOut): self
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    public static function instance(): self
    {
        if (!self::$instance) {
            return new self();
        }
        return self::$instance;
    }

    /**
     *
     * 商家自用应用获取accessToken
     *
     * 返回报文:
     * {"access_token":"xxx","expires_in":86400}
     *
     * @return bool|string 成功时返回，其他抛异常
     * @throws NuonuoException
     */
    public function getMerchantToken()
    {
        //检测必填参数
        Utils::checkParam($this->appKey, "AppKey不能为空");
        Utils::checkParam($this->appSecret, "AppSecret不能为空");
        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];
        $params  = [
            "client_id"     => $this->appKey,
            "client_secret" => $this->appSecret,
            "grant_type"    => "client_credentials" // 授权类型，此值固定为“client_credentials”
        ];
        return Utils::postCurl(self::$AUTH_URL, http_build_query($params), $headers, $this->timeOut);
    }

    /**
     * ISV应用获取accessToken
     *
     * 返回报文:
     * {"access_token":"xxx","expires_in":86400,"refresh_token":"xxx","userId":"xxx","oauthUser":"{\"userName\":\"xxx\",\"registerType\":\"1\"}","userName":"xxx","registerType":"1"}
     *
     * @param string $code 临时授权码
     * @param string $taxNum 授权商户税号
     * @param string $redirectUri 授权回调地址
     * @return bool|string 成功时返回，其他抛异常
     * @throws NuonuoException
     */
    public function getISVToken(
        string $code,
        string $taxNum,
        string $redirectUri
    ) {
        //检测必填参数
        Utils::checkParam($this->appKey, "AppKey不能为空");
        Utils::checkParam($this->appSecret, "AppSecret不能为空");
        Utils::checkParam($code, "code不能为空");
        Utils::checkParam($taxNum, "taxNum不能为空");
        Utils::checkParam($redirectUri, "redirectUri不能为空");
        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];
        $params  = [
            "client_id"     => $this->appKey,
            "client_secret" => $this->appSecret,
            "code"          => $code,
            "taxNum"        => $taxNum,
            "redirect_uri"  => $redirectUri,
            "grant_type"    => "authorization_code"
        ];
        return Utils::postCurl(self::$AUTH_URL, http_build_query($params), $headers, $this->timeOut);
    }

    /**
     * ISV应用刷新accessToken
     *
     * 返回报文:
     * {"access_token":"xxx","refresh_token":"xxx","expires_in":86400}
     *
     * @param string $refreshToken 调用令牌
     * @param string $userId oauthUser中的userId
     * @return string|bool 成功时返回，其他抛异常
     * @throws NuonuoException
     */
    public function refreshISVToken(string $refreshToken, string $userId)
    {
        Utils::checkParam($userId, "userId不能为空");
        Utils::checkParam($this->appSecret, "appSecret不能为空");
        Utils::checkParam($refreshToken, "refreshToken不能为空");
        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];
        $params  = [
            "client_id"     => $userId,
            "client_secret" => $this->appSecret,
            "refresh_token" => $refreshToken,
            "grant_type"    => "refresh_token"
        ];
        return Utils::postCurl(self::$AUTH_URL, http_build_query($params), $headers, $this->timeOut);
    }

    /**
     * 发送HTTP POST请求 <同步>
     * @param string $senId 流水号
     * @param string $token 授权码
     * @param string $taxNum 税号, 普通商户可不填
     * @param string $method API名称
     * @param string $content 私有参数, 标准JSON格式
     * @return string|bool 成功时返回，其他抛异常
     * @throws NuonuoException
     */
    public function sendPostSyncRequest(
        string $senId,
        string $token,
        string $taxNum,
        string $method,
        string $content
    ) {
        Utils::checkParam($senId, "senid不能为空");
        Utils::checkParam($token, "token不能为空");
        Utils::checkParam($method, "method不能为空");
        Utils::checkParam($content, "content不能为空");

        try {
            $timestamp = time();
            $nonce     = rand(10000, 1000000000);
            $finalUrl  = "{$this->getApiUrl()
            }?senid={$senId}&nonce={$nonce}&timestamp={$timestamp}&appkey={$this->appKey}";
            $urlInfo   = parse_url($this->getApiUrl());
            if ($urlInfo === false) {
                throw new NuonuoException("url解析失败");
            }
            $sign    = Utils::makeSign(
                $urlInfo["path"],
                $this->appSecret,
                $this->appKey,
                $senId,
                $nonce,
                $content,
                (string)$timestamp
            );
            $headers = [
                "Content-type:application/json",
                "X-Nuonuo-Sign:{$sign}",
                "accessToken: {$token}",
                "userTax:{$taxNum}",
                "method:{$method}",
                "sdkVer:" . self::$VERSION
            ];
            // 调用开放平台API
            return Utils::postCurl($finalUrl, $content, $headers, $this->timeOut);
        } catch (\Exception $e) {
            throw new NuonuoException("发送HTTP请求异常:" . $e->getMessage());
        }
    }
}
