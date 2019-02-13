<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-02 15:54:35 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Media extends ComponentBase
{
    public function getTemp(string $mediaId)
    {
        $res = $this->get('cgi-bin/media/get', [
            'query' => [
                'media_id' => $mediaId,
            ],
        ]);

        $this->checkResponse($res, null, false);
        return $res->getBody();
    }

    public function uploadTemp($file, string $type = 'image')
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
