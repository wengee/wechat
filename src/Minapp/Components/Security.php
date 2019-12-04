<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-04 11:19:48 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Security extends ComponentBase
{
    public const MEDIA_VOICE = 1;

    public const MEDIA_IMAGE = 2;

    public function checkImg($file)
    {
        if (is_string($file)) {
            $file = fopen($file, 'r');
        }

        if (!($file instanceof StreamInterface)) {
            $file = new Stream($file);
        }

        $res = $this->post('wxa/img_sec_check', [
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' => $file,
                ],
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function checkMsg(string $content)
    {
        $res = $this->post('wxa/msg_sec_check', [
            'json' => [
                'content' => $content,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function checkMediaAsync(string $mediaUrl, int $mediaType = 1)
    {
        $res = $this->post('wxa/media_check_async', [
            'json' => [
                'media_url' => $mediaUrl,
                'media_type' => $mediaType,
            ],
        ]);

        return $this->checkResponse($res, [
            'trace_id' => 'traceId',
        ]);
    }
}
