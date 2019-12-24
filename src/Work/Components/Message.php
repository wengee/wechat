<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-24 15:54:17 +0800
 */
namespace fwkit\Wechat\Work\Components;

use Exception;
use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;
use fwkit\Wechat\Utils\Items\MiniprogramNotice;
use fwkit\Wechat\Utils\Items\MpNews;
use fwkit\Wechat\Utils\Items\News;
use fwkit\Wechat\Utils\Items\TaskCard;
use fwkit\Wechat\Utils\Items\TextCard;

class Message extends ComponentBase
{
    public function sendText(int $agentId, $user, string $text)
    {
        return $this->send($agentId, $user, 'text', [
            'content' => $text,
        ]);
    }

    public function sendImage(int $agentId, $user, string $mediaId)
    {
        return $this->send($agentId, $user, 'image', [
            'media_id' => $mediaId,
        ]);
    }

    public function sendVideo(int $agentId, $user, string $mediaId, string $title = '', string $description = '')
    {
        return $this->send($agentId, $user, 'video', [
            'media_id' => $mediaId,
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function sendFile(int $agentId, $user, string $mediaId)
    {
        return $this->send($agentId, $user, 'file', [
            'media_id' => $mediaId,
        ]);
    }

    public function sendTextCard(int $agentId, $user, $data)
    {
        if (is_array($data)) {
            $data = new TextCard($data);
        }

        if (!($data instanceof TextCard)) {
            throw new Exception('Params not valid');
        }

        return $this->send($agentId, $user, 'textcard', $data);
    }

    public function sendNews(int $agentId, $user, $news)
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

        return $this->send($agentId, $user, 'news', [
            'articles' => $news,
        ]);
    }

    public function sendMpNews(int $agentId, $user, $news)
    {
        if (is_array($news)) {
            if (Helper::isAssoc($news)) {
                $news = [new MpNews($news)];
            } else {
                $news = array_map(function ($item) {
                    if (is_array($item)) {
                        return new MpNews($item);
                    } elseif ($item instanceof MpNews) {
                        return $item;
                    } else {
                        return null;
                    }
                }, $news);
            }
        } elseif ($news instanceof MpNews) {
            $news = [$news];
        } else {
            throw new Exception('Params not valid');
        }

        return $this->send($agentId, $user, 'mpnews', [
            'articles' => $news,
        ]);
    }

    public function sendMarkdown(int $agentId, $user, string $content)
    {
        return $this->send($agentId, $user, 'markdown', [
            'content' => $content,
        ]);
    }

    public function sendMiniprogramNotice(int $agentId, $user, $data)
    {
        if (is_array($data)) {
            $data = new MiniprogramNotice($data);
        }

        if (!($data instanceof MiniprogramNotice)) {
            throw new Exception('Params not valid');
        }

        return $this->send($agentId, $user, 'miniprogram_notice', $data);
    }

    public function sendTaskCard(int $agentId, $user, $data)
    {
        if (is_array($data)) {
            $data = new TaskCard($data);
        }

        if (!($data instanceof TaskCard)) {
            throw new Exception('Params not valid');
        }

        return $this->send($agentId, $user, 'taskcard', $data);
    }

    public function send(int $agentId, $user, string $msgType, $data)
    {
        $userData = $this->parseUser($user);
        $msgData = array_merge($userData, [
            'msgtype'   => $msgType,
            $msgType    => $data,
            'agentid'   => $agentId,
        ]);

        $res = $this->post('cgi-bin/message/send', [
            'json' => $msgData,
        ]);

        return $this->checkResponse($res, [
            'invaliduser' => 'invalidUser',
            'invalidparty' => 'invalidParty',
            'invalidtag' => 'invalidTag',
        ]);
    }

    protected function parseUser($data): array
    {
        $ret = [];
        if (is_array($data)) {
            $isList = true;
            if (isset($data['user'])) {
                $isList = false;
                $ret['touser'] = is_array($data['user']) ? implode('|', $data['user']) : strval($data['user']);
            }

            if (isset($data['party'])) {
                $isList = false;
                $ret['toparty'] = is_array($data['party']) ? implode('|', $data['party']) : strval($data['party']);
            }

            if (isset($data['tag'])) {
                $isList = false;
                $ret['totag'] = is_array($data['tag']) ? implode('|', $data['tag']) : strval($data['tag']);
            }

            if ($isList) {
                $ret['touser'] = implode('|', $data);
            }
        } else {
            $ret['touser'] = strval($data);
        }

        return $ret;
    }
}
