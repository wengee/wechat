<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:06:15 +0800
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

        if (method_exists($this, 'cacheGet') && !$forceUpdate) {
            $accessToken = $this->cacheGet('accessToken');
        }

        if (empty($accessToken) && static::$tokenGetter && is_callable(static::$tokenGetter)) {
            $accessToken = call_user_func(static::$tokenGetter, $this->appId);
        }

        if (empty($accessToken)) {
            $tokenComponent = $this->component($this->tokenComponent);
            if ($tokenComponent) {
                try {
                    $res = $tokenComponent->getAccessToken();
                    $accessToken = $res->get('accessToken', null);
                    if (method_exists($this, 'cacheSet')) {
                        $ttl = (int) max(1, $res->get('expiresIn', 0) - 600);
                        $this->cacheSet('accessToken', $accessToken, $ttl);
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
