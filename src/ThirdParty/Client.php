<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-04 14:33:59 +0800
 */

namespace fwkit\Wechat\ThirdParty;

use Exception;
use fwkit\Wechat\ClientBase;
use fwkit\Wechat\Utils\Cache;

class Client extends ClientBase implements ThirdClientInterface
{
    protected $type = self::TYPE_THIRD_PARTY;

    protected $baseUri = 'https://api.weixin.qq.com/';

    protected $componentList = [
        'oauth'     => Components\OAuth::class,
        'option'    => Components\Option::class,
        'token'     => Components\Token::class,
    ];

    protected $authorizerRefreshTokens = [];

    public function setComponentVerifyTicket(string $ticket): void
    {
        $cacheKey = $this->appId . ':' . $this->appSecret;
        Cache::set($cacheKey, 'componentVerifyTicket', $ticket, 86400 * 30);
    }

    public function getComponentVerifyTicket(): string
    {
        $cacheKey = $this->appId . ':' . $this->appSecret;
        $componentVerifyTicket = Cache::get($cacheKey, 'componentVerifyTicket');
        return $componentVerifyTicket ?: '';
    }

    public function setAuthorizerRefreshToken(string $appId, ?string $refreshToken = null)
    {
        if ($refreshToken) {
            $this->authorizerRefreshTokens[$appId] = $refreshToken;
        } else {
            unset($this->authorizerRefreshTokens[$appId]);
        }

        return $this;
    }

    public function getAuthorizerAccessToken(string $appId)
    {
        if (!$appId) {
            throw new Exception('AppID is required.');
        }

        $refreshToken = $this->authorizerRefreshTokens[$appId] ?? null;
        if (!$refreshToken) {
            throw new Exception('Can not find the refresh token');
        }

        $ret = $this->component('oauth')->refreshToken($appId, $refreshToken);
        $this->authorizerRefreshTokens[$appId] = $ret['refreshToken'];
        return $ret;
    }
}
