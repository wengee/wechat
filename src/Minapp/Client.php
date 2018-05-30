<?php

namespace fwkit\Wechat\Minapp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $host = 'https://api.weixin.qq.com/';

    protected $componentPrefix = '\fwkit\Wechat\Minapp\Components';
}
