<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-23 10:41:07 +0800
 */
namespace fwkit\Wechat\Provider\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Cache;

class Token extends ComponentBase
{
    protected $_verifyTicket;

    public function saveVerifyTicket(string $ticket)
    {
        $this->_verifyTicket = $ticket;
        Cache::set($this->client->getAppId(), 'verifyTicket', $ticket, 86400 * 30);
    }

    public function getVerifyTicket()
    {
        if (!$this->_verifyTicket) {
            $this->_verifyTicket = Cache::get($this->client->getAppId(), 'verifyTicket');
        }

        return $this->_verifyTicket;
    }

    public function getAccessToken()
    {
        $options = [
            'json' => [
                'component_appid' => $this->client->getAppId(),
                'component_appsecret' => $this->client->getAppSecret(),
                'component_verify_ticket' => $this->getVerifyTicket(),
            ],
        ];

        $res = $this->get('cgi-bin/component/api_component_token', $options, false);
        return $this->checkResponse($res, [
            'component_access_token' => 'accessToken',
            'expires_in' => 'expiresIn',
        ]);
    }
}
