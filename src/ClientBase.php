<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:59:18 +0800
 */
namespace fwkit\Wechat;

use fwkit\Wechat\Concerns\HasAccessToken;
use fwkit\Wechat\Concerns\HasCache;
use fwkit\Wechat\Concerns\HasHttpRequests;
use fwkit\Wechat\Concerns\HasOptions;
use fwkit\Wechat\Message\MessageBase;
use fwkit\Wechat\Utils\ErrorCode;
use fwkit\Wechat\Utils\MsgCrypt;
use Psr\Http\Message\ServerRequestInterface;

abstract class ClientBase
{
    use HasAccessToken, HasCache, HasHttpRequests, HasOptions;

    protected $componentList = [];

    protected $appId;

    protected $appSecret;

    protected $token;

    protected $encodingAESKey;

    protected $cryptor = null;

    protected $mchId;

    protected $mchSecret;

    protected $sslCert;

    protected $sslKey;

    protected $components = [];

    final public function __construct(array $options)
    {
        $this->setOptions($options);
        if ($this->encodingAESKey && $this->token && $this->appId) {
            $this->cryptor = new MsgCrypt(
                $this->token,
                $this->encodingAESKey,
                $this->appId
            );
        }

        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    public function checkSignature(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $signature = $query['signature'] ?? '';
        $timestamp = $query['timestamp'] ?? '';
        $nonce = $query['nonce'] ?? '';

        if (!$signature || !$timestamp || !$nonce) {
            throw new OfficialError('Params is invalid.');
        }

        $tmpList = [$this->token, $timestamp, $nonce];
        sort($tmpList, SORT_STRING);
        $tmpStr = implode('', $tmpList);
        if (sha1($tmpStr) !== $signature) {
            throw new OfficialError('Signature is invalid.');
        }

        return true;
    }

    public function parseMessage(string $message)
    {
        $message = MessageBase::factory($message);
        $message->setCryptor($this->cryptor);
        return $message;
    }

    public function fetchMessage(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $body = (string) $request->getBody();

        $message = '';
        $encryptType = $query['encrypt_type'] ?? null;
        if ($encryptType && $this->cryptor) {
            $msgSignature = $query['msg_signature'] ?? '';
            $timestamp = $query['timestamp'] ?? '';
            $nonce = $query['nonce'] ?? '';

            $errcode = $this->cryptor->decrypt(
                $msgSignature,
                $timestamp,
                $nonce,
                $body,
                $message
            );

            if ($errcode !== ErrorCode::OK) {
                throw new OfficialError('Decrypt error ' . $errcode);
            }
        } else {
            $this->checkSignature($request);
            $message = $body;
        }

        return $this->parseMessage($message);
    }

    public function get(string $name)
    {
        return $this->component($name);
    }

    public function component(string $name)
    {
        if (isset($this->components[$name])) {
            return $this->components[$name];
        }

        $className = $this->componentList[$name] ?? null;
        if ($className !== null) {
            $component = new $className;
            $component->setClient($this);
            $this->components[$name] = $component;
            return $component;
        }

        return null;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function getMchId()
    {
        return $this->mchId;
    }

    public function getMchSecret()
    {
        return $this->mchSecret;
    }
}
