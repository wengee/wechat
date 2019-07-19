<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-19 14:57:27 +0800
 */
namespace fwkit\Wechat\Work;

use fwkit\Wechat\ClientBase;
use fwkit\Wechat\OfficialError;
use fwkit\Wechat\Utils\ErrorCode;
use Psr\Http\Message\ServerRequestInterface;

class Client extends ClientBase
{
    protected $componentList = [
        'agent'         => Components\Agent::class,
        'base'          => Components\Base::class,
        'batch'         => Components\Batch::class,
        'department'    => Components\Department::class,
        'jsapi'         => Components\JsApi::class,
        'media'         => Components\Media::class,
        'menu'          => Components\Menu::class,
        'message'       => Components\Message::class,
        'oauth'         => Components\OAuth::class,
        'redpack'       => Components\Redpack::class,
        'tag'           => Components\Tag::class,
        'token'         => Components\Token::class,
        'user'          => Components\User::class,
    ];

    protected $baseUri = 'https://qyapi.weixin.qq.com/';

    public function checkSignature(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $signature = $query['msg_signature'] ?? '';
        $timestamp = $query['timestamp'] ?? '';
        $nonce = $query['nonce'] ?? '';
        $echoStr = $query['echostr'] ?? '';

        if (!$signature || !$timestamp || !$nonce || !$echoStr) {
            throw new OfficialError('Params is invalid.');
        }

        $ret = $this->cryptor->verifyUrl($signature, $timestamp, $nonce, $echoStr, $replyEchoStr);
        if ($ret !== ErrorCode::OK) {
            throw new OfficialError('Signature is invalid.');
        }

        return $replyEchoStr;
    }

    public function fetchMessage(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $body = (string) $request->getBody();

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

        $message = $this->parseMessage($message);
        $message->setCryptor($this->cryptor);
        return $message;
    }
}
