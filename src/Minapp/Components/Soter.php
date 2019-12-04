<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-04 11:37:59 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

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
