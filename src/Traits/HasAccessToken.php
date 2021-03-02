<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-03-02 10:11:54 +0800
 */
namespace fwkit\Wechat\Traits;

use fwkit\Wechat\Utils\Cache;

trait HasAccessToken
{
    protected static $tokenGetter;

    protected $tokenComponent = 'token';

    public function getAccessToken(bool $forceUpdate = false)
    {
        $cacheKey = $this->appId . ':' . $this->appSecret;
        $accessToken = null;
        if (!$forceUpdate) {
            $accessToken = Cache::get($cacheKey, 'accessToken');
        }

        if (empty($accessToken)) {
            if (static::$tokenGetter && is_callable(static::$tokenGetter)) {
                $accessToken = call_user_func(static::$tokenGetter, $cacheKey);
            } else {
                $tokenComponent = $this->component($this->tokenComponent);
                try {
                    $res = $tokenComponent->getAccessToken();
                    $accessToken = $res->get('accessToken', null);

                    $ttl = (int) max(1, $res->get('expiresIn', 0) - 600);
                    $expiresIn = time() + $ttl;

                    Cache::set($cacheKey, 'accessToken', $accessToken, $ttl);
                    Cache::set($cacheKey, 'accessToken_expiresIn', $expiresIn, $ttl);
                } catch (\Exception $e) {
                }
            }
        }

        return $accessToken;
    }

    public function getAccessTokenExpiresIn(): int
    {
        return (int) Cache::get(
            $this->appId . ':' . $this->appSecret,
            'accessToken_expiresIn'
        );
    }

    public static function setTokenGetter(callable $func): void
    {
        static::$tokenGetter = $func;
    }
}
