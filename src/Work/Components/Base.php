<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Base extends ComponentBase
{
    public function callbackIp()
    {
        $res = $this->get('cgi-bin/getcallbackip');
        $res = $this->checkResponse($res, ['ip_list' => 'ipList']);
        return $res->ipList;
    }
}
