<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Provider\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Cache;

class Token extends ComponentBase
{
    protected $_verifyTicket;

    public function saveVerifyTicket(string $ticket): void
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
        $res = $this->get('cgi-bin/component/api_component_token', [
            'json' => [
                'component_appid' => $this->client->getAppId(),
                'component_appsecret' => $this->client->getAppSecret(),
                'component_verify_ticket' => $this->getVerifyTicket(),
            ],
        ], false);
        return $this->checkResponse($res, [
            'component_access_token' => 'accessToken',
            'expires_in' => 'expiresIn',
        ]);
    }
}
