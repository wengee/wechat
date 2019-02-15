<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 17:40:02 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;
use fwkit\Wechat\Utils\Items\News;

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

    public function sendText(string $openId, string $content, ?string $kf = null)
    {
        return $this->send($openId, 'text', [
            'content' => $content,
        ], $kf);
    }

    public function sendImage(string $openId, string $mediaId, ?string $kf = null)
    {
        return $this->send($openId, 'image', [
            'media_id' => $mediaId,
        ], $kf);
    }

    public function sendVoice(string $openId, string $mediaId, ?string $kf = null)
    {
        return $this->send($openId, 'voice', [
            'media_id' => $mediaId,
        ], $kf);
    }

    public function sendVideo(string $openId, string $mediaId, ?string $thumbMediaId = null, ?string $title = null, ?string $description = null, ?string $kf = null)
    {
        return $this->send($openId, 'video', [
            'media_id' => $mediaId,
            'thumb_media_id' => $thumbMediaId,
            'title' => $title,
            'description' => $description,
        ], $kf);
    }

    public function sendMusic(string $openId, string $url, ?string $hqUrl = null, ?string $thumbMediaId = null, ?string $title = null, ?string $description = null, ?string $kf = null)
    {
        return $this->send($openId, 'music', [
            'musicurl' => $url,
            'hqmusicurl' => $hqUrl,
            'thumb_media_id' => $thumbMediaId,
            'title' => $title,
            'description' => $description,
        ], $kf);
    }

    public function sendNews(string $openId, $news, ?string $kf = null)
    {
        if (is_array($news)) {
            if (Helper::isAssoc($news)) {
                $news = [new News($news)];
            } else {
                $news = array_map(function ($item) {
                    if (is_array($item)) {
                        return new News($item);
                    } elseif ($item instanceof News) {
                        return $item;
                    } else {
                        return null;
                    }
                }, $news);
            }
        } elseif ($news instanceof News) {
            $news = [$news];
        } else {
            throw new \Exception('Params not valid');
        }

        return $this->send($openId, 'news', [
            'articles' => $news,
        ], $kf);
    }

    public function sendMpNews(string $openId, string $mediaId, ?string $kf = null)
    {
        return $this->send($openId, 'mpnews', [
            'media_id' => $mediaId,
        ], $kf);
    }

    public function sendMsgMenu(string $openId, string $head, string $tail, array $buttons, ?string $kf = null)
    {
        $list = [];
        foreach ($buttons as $key => $value) {
            $list[] = ['id' => $key, 'content' => $value];
        }

        return $this->send($openId, 'msgmenu', [
            'head_content' => $head,
            'list' => $list,
            'tail_content' => $tail,
        ], $kf);
    }

    public function sendWxCard(string $openId, string $cardId, ?string $kf = null)
    {
        return $this->send($openId, 'wxcard', [
            'card_id' => $cardId,
        ], $kf);
    }

    public function sendMiniprogram(string $openId, string $title, string $page, string $mediaId, ?string $kf = null)
    {
        return $this->send($openId, 'miniprogrampage', [
            'title' => $title,
            'pagepath' => $page,
            'thumb_media_id' => $mediaId,
        ], $kf);
    }

    public function send(string $openId, string $msgType, array $data, ?string $kf = null)
    {
        $data = [
            'touser' => $openId,
            'msgtype' => $msgType,
            $msgType => $data,
        ];

        if ($kf !== null) {
            $data['customservice'] = ['kf_account' => $kf];
        }

        $res = $this->post('cgi-bin/message/custom/send', [
            'json' => $data,
        ]);

        $this->checkResponse($res);
        return true;
    }
}
