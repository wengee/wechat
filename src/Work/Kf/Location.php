<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-13 10:52:54 +0800
 */

namespace fwkit\Wechat\Work\Kf;

class Location extends MessageBase
{
    public $latitude;

    public $longitude;

    public $name;

    public $address;
}
