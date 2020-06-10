<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:40:28 +0800
 */

namespace fwkit\Wechat;

use fwkit\Wechat\Traits\HasAccessToken;
use fwkit\Wechat\Traits\HasHttpRequests;
use fwkit\Wechat\Traits\HasOptions;
use fwkit\Wechat\Message\MessageBase;
use fwkit\Wechat\Utils\ErrorCode;
use fwkit\Wechat\Utils\MsgCrypt;
use Psr\Http\Message\ServerRequestInterface;

abstract class ClientBase
{
    public const TYPE_MP = 'mp';

    public const TYPE_MINAPP = 'minapp';

    public const TYPE_WORK = 'work';

    public const TYPE_THIRD_PARTY = 'thirdParty';

    use HasAccessToken, HasHttpRequests, HasOptions;

    protected $type;

    protected $componentList = [];

    protected $originId;

    protected $appId;

    protected $appSecret;

    protected $token;

    protected $encodingAESKey;

    protected $cryptor = null;

    protected $mchConfig = [];

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

    public function getType(): ?string
    {
        return $this->type;
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
        return MessageBase::factory($message);
    }

    public function fetchMessage(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $body = (string) $request->getBody();

        $message = '';
        $encryptType = $query['encrypt_type'] ?? null;
        if ($encryptType && $this->cryptor) {
            $msgSignature = $query['msg_signature'] ?? '';
            $timestamp = intval($query['timestamp'] ?? 0);
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

            $message = $this->parseMessage($message);
            $message->setCryptor($this->cryptor);
            return $message;
        } else {
            $this->checkSignature($request);
            return $this->parseMessage($body);
        }
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

    public function getOriginId()
    {
        return $this->originId;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function mchConfig(array $config = [], ?string $key = null, $default = null)
    {
        $config += $this->mchConfig;
        if ($key === null) {
            return $config;
        }

        return $config[$key] ?? $default;
    }
}
