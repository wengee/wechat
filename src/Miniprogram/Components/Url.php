<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-03 16:36:31 +0800
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

    public function queryScheme(string $scheme, int $queryType = 0)
    {
        $res = $this->post('wxa/queryscheme', [
            'json' => [
                'scheme'     => $scheme,
                'query_type' => $queryType,
            ],
        ]);

        return $this->checkResponse($res, [
            'scheme_info'        => 'schemeInfo',
            'appid'              => 'appId',
            'create_time'        => 'createTime',
            'expire_time'        => 'expireTime',
            'env_version'        => 'envVersion',
            'quota_info'         => 'quotaInfo',
            'remain_visit_quota' => 'remainVisitQuota',
        ]);
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

    public function queryLink(string $link, int $queryType = 0)
    {
        $res = $this->post('wxa/query_urllink', [
            'json' => [
                'url_link'   => $link,
                'query_type' => $queryType,
            ],
        ]);

        return $this->checkResponse($res, [
            'url_link_info'      => 'linkInfo',
            'appid'              => 'appId',
            'create_time'        => 'createTime',
            'expire_time'        => 'expireTime',
            'env_version'        => 'envVersion',
            'quota_info'         => 'quotaInfo',
            'remain_visit_quota' => 'remainVisitQuota',
        ]);
    }

    public function generateShortLink(string $url, string $title = '', bool $permanent = false): ?string
    {
        $res = $this->post('wxa/genwxashortlink', [
            'json' => [
                'page_url'     => $url,
                'page_title'   => $title,
                'is_permanent' => $permanent,
            ],
        ]);

        $this->checkResponse($res);

        return $res->get('link');
    }
}
