<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 18:35:09 +0800
 */
namespace fwkit\Wechat\Provider\Components;

use fwkit\Wechat\ComponentBase;

class Account extends ComponentBase
{
    public function getAuthorizerInfo(string $appId)
    {
        $res = $this->post('cgi-bin/component/api_get_authorizer_info', [
            'json' => [
                'component_appid' => $this->client->getAppId(),
                'authorizer_appid' => $appId,
            ],
        ]);

        return $this->checkResponse($res, [
            'authorizer_info' => 'authorizerInfo',
            'nick_name' => 'nickname',
            'head_img' => 'headImg',
            'service_type_info' => 'serviceTypeInfo',
            'verify_type_info' => 'verifyTypeInfo',
            'user_name' => 'username',
            'principal_name' => 'principalName',
            'business_info' => 'businessInfo',
            'open_store' => 'openStore',
            'open_scan' => 'openScan',
            'open_pay' => 'openPay',
            'open_card' => 'openCard',
            'open_shake' => 'openShake',
            'qrcode_url' => 'qrcodeUrl',
            'authorization_info' => 'authorizationInfo',
            'authorization_appid' => 'authorizationAppid',
            'func_info' => 'funcInfo',
            'funcscope_category' => 'funcScopeCategory',
        ]);
    }
}
