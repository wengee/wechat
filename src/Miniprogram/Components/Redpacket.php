<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-12 09:51:32 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Redpacket extends ComponentBase
{
    public function getCoverUrl(string $openId, string $cToken): ?array
    {
        $res = $this->post('redpacketcover/wxapp/cover_url/get_by_token', [
            'json' => [
                'openid' => $openId,
                'ctoken' => $cToken,
            ],
        ]);

        $this->checkResponse($res);

        return $res->get('data');
    }
}
