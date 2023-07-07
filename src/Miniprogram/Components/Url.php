<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-12 09:47:25 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Url extends ComponentBase
{
    public const EXPIRE_TYPE_TIMESTAMP = 0;
    public const EXPIRE_TYPE_INTERVAL  = 1;

    /**
     * @param null|array|string $jumpWxa
     */
    public function generateScheme($jumpWxa = null, int $expireType = 0, int $ttl = 0): ?string
    {
        $postData = ['is_expire' => false, 'expire_type' => $expireType];
        $jumpWxa  = $jumpWxa ?: null;
        if (is_string($jumpWxa)) {
            $jumpWxa = ['path' => $jumpWxa];
        }

        if ($jumpWxa) {
            $postData['jump_wxa'] = $jumpWxa;
        }

        if ($ttl > 0) {
            $postData['is_expire'] = true;
            if (self::EXPIRE_TYPE_TIMESTAMP === $expireType) {
                $postData['expire_time'] = $ttl;
            } elseif (self::EXPIRE_TYPE_INTERVAL === $expireType) {
                $postData['expire_interval'] = $ttl;
            }
        }

        $res = $this->post('wxa/generatescheme', [
            'json' => $postData,
        ]);

        $this->checkResponse($res);

        return $res->get('openlink');
    }

    /**
     * @param null|array|string $jumpWxa
     */
    public function generateLink($jumpWxa = null, int $expireType = 0, int $ttl = 0, ?array $cloudBase = null): ?string
    {
        $postData = ['is_expire' => false, 'expire_type' => $expireType, 'cloud_base' => $cloudBase];

        if (is_string($jumpWxa)) {
            $postData['path'] = $jumpWxa;
        } elseif (is_array($jumpWxa)) {
            $postData['path']        = $jumpWxa['path'] ?? null;
            $postData['query']       = $jumpWxa['query'] ?? null;
            $postData['env_version'] = $jumpWxa['version'] ?? 'release';
        }

        if ($ttl > 0) {
            $postData['is_expire'] = true;
            if (self::EXPIRE_TYPE_TIMESTAMP === $expireType) {
                $postData['expire_time'] = $ttl;
            } elseif (self::EXPIRE_TYPE_INTERVAL === $expireType) {
                $postData['expire_interval'] = $ttl;
            }
        }

        $res = $this->post('wxa/generate_urllink', [
            'json' => $postData,
        ]);

        $this->checkResponse($res);

        return $res->get('url_link');
    }
}
