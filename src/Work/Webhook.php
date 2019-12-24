<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-24 15:53:16 +0800
 */
namespace fwkit\Wechat\Work;

use Exception;
use fwkit\Wechat\OfficialError;
use fwkit\Wechat\Traits\HasHttpRequests;
use fwkit\Wechat\Utils\Helper;
use fwkit\Wechat\Utils\Items\News;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Webhook
{
    use HasHttpRequests;

    protected $baseUri = 'https://qyapi.weixin.qq.com/';

    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function sendText(string $content, $mentioned = null): bool
    {
        return $this->send('text', [
            'content'   => $content,
        ], $mentioned);
    }

    public function sendMarkdown(string $content, $mentioned = null): bool
    {
        return $this->send('markdown', [
            'content'   => $content,
        ], $mentioned);
    }

    public function sendImage($image, $mentioned = null): bool
    {
        if (is_resource($image)) {
            $image = new Stream($image);
        }

        if ($image instanceof StreamInterface) {
            $image = $image->getContents();
        }

        $image = base64_encode($image);
        $md5 = md5($image);
        return $this->send('image', [
            'base64'    => $image,
            'md5'       => $md5,
        ], $mentioned);
    }

    public function sendNews($news, $mentioned = null): bool
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
            throw new Exception('Params not valid');
        }

        return $this->send('news', [
            'articles' => $news,
        ], $mentioned);
    }

    public function send(string $msgType, array $msgData, $mentioned = null): bool
    {
        $msgData += $this->parseMentioned($mentioned);
        $res = $this->request('POST', 'cgi-bin/message/send', [
            'json' => [
                'msgtype'   => $msgType,
                $msgType    => $msgData,
            ],
        ], false, 'json');

        if (!$res) {
            throw new OfficialError('An unknown error occurred.');
        } elseif (!empty($res['errcode'])) {
            throw new OfficialError($res['errmsg'], $res['errcode']);
        }

        return true;
    }

    protected function parseMentioned($mentioned = null): array
    {
        if (!$mentioned) {
            return [];
        }

        $ret = ['mentioned_list' => [], 'mentioned_mobile_list' => []];
        $mentioned = is_array($mentioned) ? $mentioned : [$mentioned];
        foreach ($mentioned as $m) {
            $m = strval($m);
            if (preg_match('/^1(([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/', $m)) {
                $ret['mentioned_mobile_list'][] = $m;
            } else {
                $ret['mentioned_list'][] = $m;
            }
        }

        return $ret;
    }
}
