<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Qrcode extends ComponentBase
{
    public function create($scene, int $expireIn = 0)
    {
        $actionName = $expireIn > 0 ? 'QR_' : 'QR_LIMIT_';
        if (is_int($scene)) {
            $actionName .= 'SCENE';
            $actionInfo = ['scene' => ['scene_id' => $scene]];
        } else {
            $actionName .= 'STR_SCENE';
            $actionInfo = ['scene' => ['scene_str' => strval($scene)]];
        }

        $params = [
            'action_name' => $actionName,
            'action_info' => $actionInfo,
        ];

        $expireIn > 0 && $params['expire_seconds'] = intval($expireIn);

        $res = $this->post('qrcode/create')
                    ->withJson($params)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function url(string $ticket)
    {
        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . rawurlencode($ticket);
    }

    public function download(string $ticket, ?string $saveTo = null)
    {
        $res = $this->get('showqrcode')
                    ->withQuery(['ticket' => $ticket]);

        if ($saveTo === null) {
            return $res->getText();
        } else {
            $res->download($saveTo);
        }
    }
}
