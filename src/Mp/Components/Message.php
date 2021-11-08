<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-08 10:49:36 +0800
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
                'touser'  => $openId,
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
            'media_id'       => $mediaId,
            'thumb_media_id' => $thumbMediaId,
            'title'          => $title,
            'description'    => $description,
        ], $kf);
    }

    public function sendMusic(string $openId, string $url, ?string $hqUrl = null, ?string $thumbMediaId = null, ?string $title = null, ?string $description = null, ?string $kf = null)
    {
        return $this->send($openId, 'music', [
            'musicurl'       => $url,
            'hqmusicurl'     => $hqUrl,
            'thumb_media_id' => $thumbMediaId,
            'title'          => $title,
            'description'    => $description,
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
                    }
                    if ($item instanceof News) {
                        return $item;
                    }

                    return null;
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

    public function sendMpNewsArticle(string $openId, string $articleId, ?string $kf = null)
    {
        return $this->send($openId, 'mpnewsarticle', [
            'article_id' => $articleId,
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
            'list'         => $list,
            'tail_content' => $tail,
        ], $kf);
    }

    public function sendWxCard(string $openId, string $cardId, ?string $kf = null)
    {
        return $this->send($openId, 'wxcard', [
            'card_id' => $cardId,
        ], $kf);
    }

    public function sendMiniprogram(string $openId, string $appId, string $title, string $pagePath, string $mediaId, ?string $kf = null)
    {
        return $this->send($openId, 'miniprogrampage', [
            'appid'          => $appId,
            'title'          => $title,
            'pagepath'       => $pagePath,
            'thumb_media_id' => $mediaId,
        ], $kf);
    }

    public function send(string $openId, string $msgType, array $data, ?string $kf = null)
    {
        $data = [
            'touser'  => $openId,
            'msgtype' => $msgType,
            $msgType  => $data,
        ];

        if (null !== $kf) {
            $data['customservice'] = ['kf_account' => $kf];
        }

        $res = $this->post('cgi-bin/message/custom/send', [
            'json' => $data,
        ]);

        $this->checkResponse($res);

        return true;
    }
}
