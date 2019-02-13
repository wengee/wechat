<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-02 15:35:48 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;

class Message extends ComponentBase
{
    public function typing(string $openId, bool $typing = true)
    {
        $res = $this->post('cgi-bin/message/custom/typing', [
            'json' => [
                'touser' => $openId,
                'command' => $typing ? 'Typing' : 'CancelTyping',
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function sendText(string $openId, string $content)
    {
        return $this->send($openId, 'text', [
            'content' => $content,
        ]);
    }

    public function sendImage(string $openId, string $mediaId)
    {
        return $this->send($openId, 'image', [
            'media_id' => $mediaId,
        ]);
    }

    public function sendLink(string $openId, string $title, string $description, string $url, string $thumbUrl)
    {
        return $this->send($openId, 'link', [
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'thumb_url' => $thumbUrl,
        ]);
    }

    public function sendMiniprogram(string $openId, string $title, string $page, string $mediaId)
    {
        return $this->send($openId, 'miniprogrampage', [
            'title' => $title,
            'pagepath' => $page,
            'thumb_media_id' => $mediaId,
        ]);
    }

    public function send(string $openId, string $msgType, array $data)
    {
        $res = $this->post('cgi-bin/message/custom/send', [
            'json' => [
                'touser' => $openId,
                $msgType => $data,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
