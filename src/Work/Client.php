<?php

namespace fwkit\Wechat\Work;

use fwkit\Utils;
use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $host = 'https://qyapi.weixin.qq.com/cgi-bin/';

    protected $componentPrefix = '\fwkit\Wechat\Work\Components';
}
