<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Soter extends ComponentBase
{
    public function verifySignature(string $openId, string $jsonString, string $jsonSignature)
    {
        $res = $this->post('wxaapi/newtmpl/addtemplate', [
            'json' => [
                'openid' => $openId,
                'json_string' => $jsonString,
                'json_signature' => $jsonSignature,
            ],
        ]);

        return $this->checkResponse($res, [
            'is_ok' => 'isOk',
        ]);
    }
}
