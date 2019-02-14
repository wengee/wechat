<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:46:24 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Location extends EventBase
{
    public $latitude;

    public $longitude;

    public $precision;
}
