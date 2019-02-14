<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 15:45:46 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class QrCode extends ComponentBase
{
    public function create($value, int $expire = 0)
    {
        $data = [];
        $expire = min(max($expire, 0), 2592000);
        if ($expire > 0) {
            $data['expire_seconds'] = $expire;
            $prefix = 'QR_';
        } else {
            $prefix = 'QR_LIMIT_';
        }

        if (is_int($value)) {
            $data['action_name'] = $prefix . 'SCENE';
            $data['action_info'] = ['scene_id' => $value];
        } else {
            $data['action_name'] = $prefix . 'STR_SCENE';
            $data['action_info'] = ['scene_str' => (string) $value];
        }

        $res = $this->post('cgi-bin/qrcode/create', [
            'json' => $data,
        ]);

        return $this->checkResponse($res, [
            'expire_seconds' => 'expire',
        ]);
    }

    public function fetch(string $ticket)
    {
        $res = $this->get('cgi-bin/showqrcode', [
            'query' => ['ticket' => $ticket],
        ], false);

        return $this->checkResponse($res, null, false);
    }
}
