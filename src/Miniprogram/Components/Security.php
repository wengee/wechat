<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-04 15:53:32 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Security extends ComponentBase
{
    public const MEDIA_VOICE = 1;
    public const MEDIA_IMAGE = 2;

    public const SCENE_PROFILE = 1;
    public const SCENE_COMMENT = 2;
    public const SCENE_POST    = 3;
    public const SCENE_BLOG    = 4;

    public function checkImg($file)
    {
        if (is_string($file)) {
            $file = fopen($file, 'r');
        }

        if (!$file instanceof StreamInterface) {
            $file = new Stream($file);
        }

        $res = $this->post('wxa/img_sec_check', [
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => $file,
                ],
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function checkMsg(string $openId, int $scene, string $content, array $extra = [])
    {
        $data = array_merge($extra, [
            'version' => 2,
            'openid'  => $openId,
            'scene'   => $scene,
            'content' => $content,
        ]);

        $res = $this->post('wxa/msg_sec_check', [
            'json' => $data,
        ]);

        return $this->checkResponse($res, [
            'trace_id' => 'traceId',
        ]);
    }

    public function checkMediaAsync(string $openId, int $scene, string $mediaUrl, int $mediaType = 2)
    {
        $res = $this->post('wxa/media_check_async', [
            'json' => [
                'media_url'  => $mediaUrl,
                'media_type' => $mediaType,
                'version'    => 2,
                'openid'     => $openId,
                'scene'      => $scene,
            ],
        ]);

        return $this->checkResponse($res, [
            'trace_id' => 'traceId',
        ]);
    }

    public function getUserRiskRank(string $openId, string $clientIp, int $scene = 0, array $extra = [])
    {
        $res = $this->post('wxa/getuserriskrank', [
            'json' => [
                'appid'         => $this->client->getAppId(),
                'openid'        => $openId,
                'scene'         => $scene,
                'mobile_no'     => $extra['mobile'] ?? '',
                'client_ip'     => $clientIp,
                'email_address' => $extra['email'] ?? '',
                'extended_info' => $extra['extendedInfo'] ?? '',
                'is_test'       => $extra['test'] ?? false,
            ],
        ]);

        return $this->checkResponse($res, [
            'risk_rank' => 'riskRank',
            'unoin_id'  => 'unionId',
        ]);
    }
}
