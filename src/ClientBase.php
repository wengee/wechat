<?php

namespace fwkit\Wechat;

use fwkit\Http;
use fwkit\Utils;
use fwkit\Wechat\Utils\MsgCrypt;
use fwkit\Wechat\Utils\ErrorCode;

abstract class ClientBase
{
    protected $host;

    protected $config;

    protected $crypto;

    protected $inputed = false;

    protected $postStr;

    protected $receiveMsg = [];

    protected $components = [];

    protected $componentPrefix = null;

    public function __construct(array $options = [])
    {
        $config = new Config($options);
        $this->config = $config;

        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }

        if ($config->encodingAESKey) {
            $this->crypter = new MsgCrypt(
                $config->token,
                $config->encodingAESKey,
                $config->appId
            );
        }
    }

    public function __get(string $property)
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        if (preg_match('/^msg(.+)$/i', $property, $matches)) {
            $value = $this->getMsg($matches[1]);
            return $value ?: $this->getMsg($property);
        }

        return null;
    }

    public function component(string $name)
    {
        if ($this->componentPrefix === null) {
            return null;
        }

        if (isset($this->components[$name])) {
            return $this->components[$name];
        }

        $class = $this->componentPrefix . '\\' . ucfirst($name);
        if (!class_exists($class)) {
            throw new \Exception('The component"' . $name . '" is not found.');
        }

        $component = Utils::newInstance($class);
        $component->setClient($this);
        $this->components[$name] = $component;
        return $component;
    }

    public function input(string $sMsg, ?string $sEncryptType = null, string $sMsgSig = '', int $sTimestamp = 0, string $sNonce = '')
    {
        if ($this->inputed) {
            return $this;
        }

        $this->inputed = true;
        if (strtolower($sEncryptType) === 'aes' && !empty($this->crypter)) {
            $postStr = $sMsg;
            $errcode = $this->crypter->decrypt($sMsgSig, $sTimeStamp, $sNonce, $postStr, $sMsg);

            if ($errcode !== ErrorCode::OK) {
                throw new \Exception('Access Denied.', $errcode);
            }
        }

        $receiveMsg = (array) simplexml_load_string($sMsg, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->postStr = $sMsg;
        $this->receiveMsg = array_change_key_case($receiveMsg, CASE_LOWER);
        return $this;
    }

    public function getMsgXML()
    {
        if ($this->inputed) {
            return $this->postStr;
        }

        return null;
    }

    public function getMsg(string $name, $defaultValue = null)
    {
        $name = strtolower($name);
        return isset($this->receiveMsg[$name]) ? $this->receiveMsg[$name] : $defaultValue;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getAppId()
    {
        return $this->config->appId;
    }

    public function reply($rawXml)
    {
        if (empty($this->crypter)) {
            return $rawXml;
        }

        $nonceStr = Utils::createNonceStr();
        $timestamp = time();
        $errcode = $this->crypter->encrypt($rawXml, $timestamp, $nonceStr, $encryptXml);
        if ($errcode !== ErrorCode::OK) {
            return $rawXml;
        }

        return $encryptXml;
    }

    public function replyMessage(string $type, $data)
    {
        if (!$this->inputed) {
            throw new \Exception('No message is inputed.');
        }

        $type = strtolower($type);
        $userData = [
            'target' => $this->getMsg('FromUserName'),
            'source' => $this->getMsg('ToUserName'),
        ];

        $reply = null;
        switch ($type) {
            case 'text':
                $data = is_string($data) ? ['content' => $data] : (array) $data;
                $data = array_merge($data, $userData);
                $reply = new Replies\TextReply($data);
                break;

            case 'image':
            case 'voice':
            case 'video':
                $data = is_string($data) ? ['mediaId' => $data] : (array) $data;
                $data = array_merge($data, $userData);
                if ($type == 'image') {
                    $reply = new Replies\ImageReply($data);
                } elseif ($type == 'voice') {
                    $reply = new Replies\VoiceReply($data);
                } else {
                    $reply = new Replies\VideoReply($data);
                }

                break;

            case 'news':
                $data = (array) $data;
                $reply = new Replies\ArticleReply($userData);
                foreach ($data as $item) {
                    $reply->addItem($item);
                }

                break;
        }

        if (empty($reply)) {
            return 'success';
        }

        $response = $reply->render();
        return $this->reply($response);
    }

    public function request(string $method, string $url, $accessToken = null)
    {
        if (!Utils::startsWith($url, ['http://', 'https://'])) {
            $url = $this->host . ltrim($url, '/');
        }

        $req = Http::request($method, $url);
        if ($accessToken !== false) {
            $accessToken = $accessToken ?: $this->getAccessToken();
            $accessToken && $req->withAccessToken($accessToken);
        }

        return $req;
    }

    public function getAccessToken()
    {
        if ($this->config->accessToken) {
            return $this->config->accessToken;
        }

        $appId = $this->config->appId;
        $cache = $this->config->cache;
        $cacheKey = 'accessToken-' . $this->config->appId;
        if (is_callable($cache)) {
            $accessToken = Utils::execute($cache, [$cacheKey]);
            if ($accessToken) {
                return $accessToken;
            }
        }

        $token = $this->component('token');
        if (!$token) return null;

        try {
            $res = $token->getAccessToken();
            $accessToken = $res->accessToken;
            if ($accessToken && is_callable($cache)) {
                $expireIn = (int) $res->expiresIn;
                Utils::execute($cache, [$cacheKey, $accessToken, $expireIn]);
            }
        } catch (\Exception $e) {
            return null;
        }

        return $accessToken;
    }
}

class Config
{
    private $_appId;

    private $_appSecret;

    private $_refreshToken;

    private $_accessToken;

    private $_encodingAESKey;

    private $_mchId;

    private $_mchKey;

    private $_certPath;

    private $_keyPath;

    private $_cache;

    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            $property = '_' . $key;
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    public function __get(string $property)
    {
        $property = '_' . $property;
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return null;
    }

    public function setToken(string $accessToken, ?string $refreshToken = null)
    {
        $this->_accessToken = $accessToken;
        if ($refreshToken !== null) {
            $this->_refreshToken = $refreshToken;
        }
    }
}
