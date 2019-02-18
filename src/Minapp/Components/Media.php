<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-18 17:09:32 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Media extends ComponentBase
{
    public function fetch(string $mediaId)
    {
        $res = $this->get('cgi-bin/media/get', [
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
}
