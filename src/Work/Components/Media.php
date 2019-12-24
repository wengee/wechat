<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-24 14:34:41 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Media extends ComponentBase
{
    public function fetch(string $mediaId, bool $jssdkVoice = false)
    {
        $url = $jssdkVoice ? 'cgi-bin/media/get/jssdk' : 'cgi-bin/media/get';

        $res = $this->get($url, [
            'query' => [
                'media_id' => $mediaId,
            ],
        ]);

        return $this->checkResponse($res, [
            'video_url' => 'videoUrl',
        ], false);
    }

    public function upload($file, string $type = 'image')
    {
        if (is_string($file)) {
            $file = fopen($file, 'r');
        }

        if (!($file instanceof StreamInterface)) {
            $file = new Stream($file);
        }

        $res = $this->post('cgi-bin/media/upload', [
            'query' => ['type' => $type],
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' => $file,
                ],
            ],
        ]);

        return $this->checkResponse($res, [
            'media_id' => 'mediaId',
            'created_at' => 'created',
        ]);
    }

    public function fetchVoice(string $mediaId)
    {
        return $this->fetch($mediaId, true);
    }
}
