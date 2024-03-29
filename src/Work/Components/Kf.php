<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-15 11:43:37 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Constants;
use fwkit\Wechat\Work\Kf\MessageBase;

class Kf extends ComponentBase
{
    public function addAccount(string $name, string $mediaId): ?string
    {
        $res = $this->post('cgi-bin/kf/account/add', [
            'json' => [
                'name'     => $name,
                'media_id' => $mediaId,
            ],
        ]);

        $res = $this->checkResponse($res);

        return $res['open_kfid'] ?? null;
    }

    public function delAccount(string $kfId): bool
    {
        $res = $this->post('cgi-bin/kf/account/del', [
            'json' => [
                'open_kfid' => $kfId,
            ],
        ]);

        $res = $this->checkResponse($res);

        return true;
    }

    public function updateAccount(string $kfId, ?string $name = null, ?string $mediaId = null): bool
    {
        if (!$name && !$mediaId) {
            return false;
        }

        $data = ['open_kfid' => $kfId];
        if ($name) {
            $data['name'] = $name;
        }

        if ($mediaId) {
            $data['media_id'] = $mediaId;
        }

        $res = $this->post('cgi-bin/kf/account/update', [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res);

        return true;
    }

    public function listAccount(int $offset = 0, int $limit = 100)
    {
        $res = $this->post('cgi-bin/kf/account/list', [
            'json' => [
                'offset' => $offset,
                'limit'  => $limit,
            ],
        ]);

        return $this->checkResponse($res, [
            'account_list' => 'accountList',
            'open_kfid'    => 'kfId',
        ]);
    }

    public function getUrl(string $kfId, ?string $scene = null): string
    {
        $data = ['open_kfid' => $kfId];
        if ($scene) {
            $data['scene'] = $scene;
        }

        $res = $this->post('cgi-bin/kf/add_contact_way', [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res);

        return $res['url'];
    }

    public function syncMsg(?string $cursor = null, ?string $token = null, int $limit = 1000, int $voice = Constants::KF_VOICE_AMR)
    {
        $data = ['limit' => $limit, 'voice_format' => $voice];
        if ($cursor) {
            $data['cursor'] = $cursor;
        }

        if ($token) {
            $data['token'] = $token;
        }

        $res = $this->post('cgi-bin/kf/sync_msg', [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res, [
            'next_cursor'     => 'nextCursor',
            'has_more'        => 'hasMore',
            'msg_list'        => 'msgList',
            'open_kfid'       => 'kfId',
            'external_userid' => 'userId',
            'send_time'       => 'sendTime',
            'msgtype'         => 'msgType',
            'msgid'           => 'msgId',
            'menu_id'         => 'menuId',
            'media_id'        => 'mediaId',
            'event_type'      => 'eventType',
            'scene_param'     => 'sceneParam',
            'welcome_code'    => 'welcomeCode',
            'wechat_channels' => 'wechatChannels',
            'fail_msgid'      => 'failMsgId',
            'fail_type'       => 'failType',
        ]);

        $res['msgList'] = array_map(function ($item) {
            return is_array($item) ? MessageBase::factory($item) : null;
        }, $res['msgList'] ?? []);

        return $res;
    }

    public function sendWelcomeTextMsg(string $code, string $content, string $msgId = ''): string
    {
        return $this->doSendTextMsg($code, '', $content, $msgId, true);
    }

    public function sendWelcomeMenuMsg(string $kfId, string $header, array $list, string $footer, string $msgId = ''): string
    {
        return $this->doSendMenuMsg($kfId, '', $header, $list, $footer, $msgId, true);
    }

    public function sendTextMsg(string $kfId, string $userId, string $content, string $msgId = ''): string
    {
        return $this->doSendTextMsg($kfId, $userId, $content, $msgId);
    }

    public function sendImageMsg(string $kfId, string $userId, string $mediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'image', ['media_id' => $mediaId], $msgId);
    }

    public function sendVoiceMsg(string $kfId, string $userId, string $mediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'voice', ['media_id' => $mediaId], $msgId);
    }

    public function sendVideoMsg(string $kfId, string $userId, string $mediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'video', ['media_id' => $mediaId], $msgId);
    }

    public function sendFileMsg(string $kfId, string $userId, string $mediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'file', ['media_id' => $mediaId], $msgId);
    }

    public function sendLinkMsg(string $kfId, string $userId, string $title, string $desc, string $url, string $thumbMediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'link', [
            'title'          => $title,
            'desc'           => $desc,
            'url'            => $url,
            'thumb_media_id' => $thumbMediaId,
        ], $msgId);
    }

    public function sendMiniprogramMsg(string $kfId, string $userId, string $appId, string $title, string $pagePath, string $thumbMediaId, string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'miniprogram', [
            'appid'          => $appId,
            'title'          => $title,
            'thumb_media_id' => $thumbMediaId,
            'pagepath'       => $pagePath,
        ], $msgId);
    }

    public function sendMenuMsg(string $kfId, string $userId, string $header, array $list, string $footer, string $msgId = ''): string
    {
        return $this->doSendMenuMsg($kfId, $userId, $header, $list, $footer, $msgId);
    }

    public function sendLocationMsg(string $kfId, string $userId, float $latitude, float $longitude, string $name = '', string $address = '', string $msgId = ''): string
    {
        return $this->sendMsg($kfId, $userId, 'location', [
            'name'      => $name,
            'address'   => $address,
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ], $msgId);
    }

    protected function doSendTextMsg(string $kfIdOrCode, string $userId, string $content, string $msgId = '', bool $isWelcomeMsg = false): string
    {
        return $this->sendMsg($kfIdOrCode, $userId, 'text', ['content' => $content], $msgId, $isWelcomeMsg);
    }

    protected function doSendMenuMsg(string $kfIdOrCode, string $userId, string $header, array $list, string $footer, string $msgId = '', bool $isWelcomeMsg = false): string
    {
        return $this->sendMsg($kfIdOrCode, $userId, 'msgmenu', [
            'head_content' => $header,
            'list'         => $list,
            'tail_content' => $footer,
        ], $msgId, $isWelcomeMsg);
    }

    protected function sendMsg(string $kfIdOrCode, string $userId, string $msgType, array $data, string $msgId = '', bool $isWelcomeMsg = false): string
    {
        if ($isWelcomeMsg && in_array($msgType, ['text', 'msgmenu'])) {
            $data = [
                'code'    => $kfIdOrCode,
                'msgtype' => $msgType,
                $msgType  => $data,
            ];

            if ($msgId) {
                $data['msgid'] = $msgId;
            }

            $res = $this->post('cgi-bin/kf/send_msg_on_event', [
                'json' => $data,
            ]);
        } else {
            $data = [
                'touser'    => $userId,
                'open_kfid' => $kfIdOrCode,
                'msgtype'   => $msgType,
                $msgType    => $data,
            ];

            if ($msgId) {
                $data['msgid'] = $msgId;
            }

            $res = $this->post('cgi-bin/kf/send_msg', [
                'json' => $data,
            ]);
        }

        $res = $this->checkResponse($res);

        return $res['msgid'];
    }
}
