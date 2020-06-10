<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Base extends ComponentBase
{
    public function shortUrl(string $url)
    {
        $res = $this->post('cgi-bin/shorturl', [
            'json' => [
                'action' => 'long2short',
                'long_url' => $url,
            ],
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
            'json' => [
                'action' => $action,
                'check_operator' => $operator,
            ],
        ]);

        return $this->checkResponse($res, [
            'real_operator' => 'realOperator',
            'from_operator' => 'fromOperator',
            'package_loss' => 'packageLoss',
        ]);
    }
}
