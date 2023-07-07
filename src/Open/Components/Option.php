<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-04 09:59:19 +0800
 */

namespace fwkit\Wechat\Open\Components;

use fwkit\Wechat\ComponentBase;

class Option extends ComponentBase
{
    public function fetch(string $authorizerAppId, string $optionName)
    {
        $res = $this->post('cgi-bin/component/api_get_authorizer_option', [
            'json' => [
                'component_appid'   => $this->client->getAppId(),
                'authorizer_appid'  => $authorizerAppId,
                'option_name'       => $optionName,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorizer_appid'  => 'authorizerAppId',
            'option_name'       => 'optionName',
            'option_value'      => 'optionValue',
        ]);
    }

    public function save(string $authorizerAppId, string $optionName, string $optionValue)
    {
        $res = $this->post('cgi-bin/component/api_set_authorizer_option', [
            'json' => [
                'component_appid'   => $this->client->getAppId(),
                'authorizer_appid'  => $authorizerAppId,
                'option_name'       => $optionName,
                'option_value'      => $optionValue,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
