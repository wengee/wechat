<?php

namespace fwkit\Wechat\Mp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $host = 'https://api.weixin.qq.com/cgi-bin/';

    protected $componentPrefix = '\fwkit\Wechat\Mp\Components';
}
