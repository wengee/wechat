<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-13 17:59:20 +0800
 */
namespace fwkit\Wechat\Concerns;

trait HasAccessToken
{
    protected static $tokenGetter;

    protected $tokenComponent = 'token';

    protected $accessToken;

    public function getAccessToken(bool $forceUpdate = false)
    {
        if ($this->accessToken && !$forceUpdate) {
            return $this->accessToken;
        }

        $accessToken = null;
        $appId = $this->appId;
        $cacheKey = 'wechat-access-token-' . $appId;

        if (method_exists($this, 'cacheGet') && !$forceUpdate) {
            $accessToken = $this->cacheGet($cacheKey);
        }

        if (empty($accessToken) && static::$tokenGetter && is_callable(static::$tokenGetter)) {
            $accessToken = call_user_func(static::$tokenGetter, $appId);
        }

        if (empty($accessToken)) {
            $tokenComponent = $this->component($this->tokenComponent);
            if ($tokenComponent) {
                try {
                    $res = $tokenComponent->getAccessToken();
                    $accessToken = $res->get('accessToken', null);
                    if (method_exists($this, 'cacheSet')) {
                        $ttl = (int) max(1, $res->get('expiresIn', 0) - 600);
                        $this->cacheSet($cacheKey, $accessToken, $ttl);
                    }
                } catch (\Exception $e) {
                }
            }
        }

        $this->accessToken = $accessToken;
        return $accessToken;
    }

    public static function setTokenGetter(callable $func)
    {
        static::$tokenGetter = $func;
    }
}
