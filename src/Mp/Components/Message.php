<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Message extends ComponentBase
{
    const ADD_KF_URL = 'https://api.weixin.qq.com/customservice/kfaccount/add';

    const UPDATE_KF_URL = 'https://api.weixin.qq.com/customservice/kfaccount/update';

    const DELETE_KF_URL = 'https://api.weixin.qq.com/customservice/kfaccount/del';

    const LIST_KF_URL = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist';

    const UPLOAD_HEADIMG_URL = 'http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg';

    public function __call(string $name, array $args)
    {
        if (preg_match('/^send(.+)$/i', $name, $matches)) {
            $type = strtolower($matches[1]);
            return $this->send($type, ...$args);
        }

        return false;
    }

    public function send(string $type, string $openId, ...$args)
    {
        $type = strtolower($type);

        $customService = null;
        if (isset($args[-1])) {
            $lastArg = $args[-1];
            if (is_array($lastArg) && isset($lastArg['kfAccount'])) {
                $customService = ['kf_account' => $lastArg['kfAccount']];
                unset($args[-1]);
            }
        }

        switch ($type) {
            case 'text':
                $data = ['content' => array_shift($args)];
                break;
            case 'image':
            case 'voice':
            case 'video':
            case 'mpnews':
                $data = ['media_id' => array_shift($args)];
                if ($type === 'video') {
                    $data['title'] = array_shift($args);
                    $data['description'] = array_shift($args);
                    $data['thumb_media_id'] = array_shift($args);
                }
                break;
            case 'music':
                $data = [];
                $data['title'] = array_shift($args);
                $data['description'] = array_shift($args);
                $data['musicurl'] = array_shift($args);
                $data['hqmusicurl'] = array_shift($args);
                $data['thumb_media_id'] = array_shift($args);
                break;
            case 'news':
                $data = ['articles' => $args];
                break;
            case 'wxcard':
                $data = ['card_id' => array_shift($args)];
                break;
            case 'minapp':
            case 'miniprogram':
            case 'miniprogrampage':
                $type = 'miniprogrampage';
                $data = [];
                $data['appid'] = array_shift($args);
                $data['pagepath'] = array_shift($args);
                $data['title'] = array_shift($args);
                $data['thumb_media_id'] = array_shift($args);
                break;
            default:
                return false;
        }

        $params = ['touser' => $openId,'msgtype' => $type, $type => $data];
        if ($customService) $params['customservice'] = $customService;

        $res = $this->post('message/custom/send')
                    ->withJson($params)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function typing(string $openId, bool $cancel = false)
    {
        $params = [
            'touser' => $openId,
            'command' => $cancel ? 'CancelTyping' : 'Typing',
        ];

        $res = $this->post('message/custom/typing')
                    ->withJson($params)
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function addKf(string $account, string $nickname, string $password)
    {
        $params = [
            'kf_account' => $account,
            'nickname' => $nickname,
            'password' => $password,
        ];

        $res = $this->post(self::ADD_KF_URL)
                    ->withJson($params)
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function updateKf(string $account, string $nickname, string $password)
    {
        $params = [
            'kf_account' => $account,
            'nickname' => $nickname,
            'password' => $password,
        ];

        $res = $this->post(self::UPDATE_KF_URL)
                    ->withJson($params)
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function deleteKf(string $account, string $nickname, string $password)
    {
        $params = [
            'kf_account' => $account,
            'nickname' => $nickname,
            'password' => $password,
        ];

        $res = $this->post(self::DELETE_KF_URL)
                    ->withJson($params)
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function listKf()
    {
        $res = $this->get(self::LIST_KF_URL)
                    ->getJson();

        $this->throwOfficialError($res);
        return $res->kfList;
    }
}
