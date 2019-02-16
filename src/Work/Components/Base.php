<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 15:52:10 +0800
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
