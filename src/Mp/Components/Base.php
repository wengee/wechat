<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 15:58:02 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Base extends ComponentBase
{
    public function shortUrl(string $url)
    {
        $res = $this->post('cgi-bin/shorturl', [
            'action' => 'long2short',
            'long_url' => $url,
        ]);

        $res = $this->checkResponse($res, ['short_url' => 'shortUrl']);
        return $res->shortUrl;
    }

    public function callbackIp()
    {
        $res = $this->get('cgi-bin/getcallbackip');
        $res = $this->checkResponse($res, ['ip_list' => 'ipList']);
        return $res->ipList;
    }

    public function check(string $action = 'all', string $operator = 'DEFAULT')
    {
        $res = $this->post('cgi-bin/callback/check', [
            'action' => $action,
            'check_operator' => $operator,
        ]);

        return $this->checkResponse($res, [
            'real_operator' => 'realOperator',
            'from_operator' => 'fromOperator',
            'package_loss' => 'packageLoss',
        ]);
    }
}
