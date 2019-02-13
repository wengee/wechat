<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-13 17:48:15 +0800
 */
namespace Wechat;

use Wechat\Concerns\HasAccessToken;
use Wechat\Concerns\HasCache;
use Wechat\Concerns\HasHttpRequests;
use Wechat\Concerns\HasOptions;
use Wechat\Message\MessageBase;
use Wechat\Utils\MsgCrypt;
use Symfony\Component\HttpFoundation\Request;

abstract class ClientBase
{
    use HasAccessToken, HasCache, HasHttpRequests, HasOptions;

    protected $componentList = [];

    protected $appId;

    protected $appSecret;

    protected $token;

    protected $encodingAESKey;

    protected $cryptor = null;

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

    public function checkSignature(Request $request)
    {
        $signature = $request->query->get('signature', '');
        $timestamp = $request->query->get('timestamp', '');
        $nonce = $request->query->get('nonce', '');

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

    public function fetchMessage(Request $request)
    {
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
}
