<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-08 16:37:49 +0800
 */

namespace fwkit\Wechat\Work;

use fwkit\Wechat\ClientBase;
use fwkit\Wechat\OfficialError;
use fwkit\Wechat\Utils\ErrorCode;
use Psr\Http\Message\ServerRequestInterface;

class Client extends ClientBase
{
    protected $type = self::TYPE_WORK;

    protected $componentList = [
        'agent'      => Components\Agent::class,
        'base'       => Components\Base::class,
        'batch'      => Components\Batch::class,
        'department' => Components\Department::class,
        'jsapi'      => Components\JsApi::class,
        'media'      => Components\Media::class,
        'menu'       => Components\Menu::class,
        'message'    => Components\Message::class,
        'oauth'      => Components\OAuth::class,
        'redpack'    => Components\Redpack::class,
        'schedule'   => Components\Schedule::class,
        'tag'        => Components\Tag::class,
        'token'      => Components\Token::class,
        'user'       => Components\User::class,
        'kf'         => Components\Kf::class,
    ];

    protected $baseUri = 'https://qyapi.weixin.qq.com/';

    public function checkSignature(ServerRequestInterface $request)
    {
        $query     = $request->getQueryParams();
        $signature = $query['msg_signature'] ?? '';
        $timestamp = $query['timestamp'] ?? '';
        $nonce     = $query['nonce'] ?? '';
        $echoStr   = $query['echostr'] ?? '';

        if (!$signature || !$timestamp || !$nonce || !$echoStr) {
            throw new OfficialError('Params is invalid.');
        }

        $ret = $this->cryptor->verifyUrl($signature, $timestamp, $nonce, $echoStr, $replyEchoStr);
        if (ErrorCode::OK !== $ret) {
            throw new OfficialError('Signature is invalid.');
        }

        return $replyEchoStr;
    }

    public function fetchMessage(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        $body  = (string) $request->getBody();

        $msgSignature = $query['msg_signature'] ?? '';
        $timestamp    = intval($query['timestamp'] ?? 0);
        $nonce        = $query['nonce'] ?? '';

        $errcode = $this->cryptor->decrypt(
            $msgSignature,
            $timestamp,
            $nonce,
            $body,
            $message
        );

        if (ErrorCode::OK !== $errcode) {
            throw new OfficialError('Decrypt error '.$errcode);
        }

        $message = $this->parseMessage($message);
        $message->setCryptor($this->cryptor);

        return $message;
    }
}
