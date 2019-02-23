<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:58:46 +0800
 */
namespace fwkit\Wechat\Provider;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $componentList = [
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';
}
