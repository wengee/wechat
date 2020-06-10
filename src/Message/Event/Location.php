<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class Location extends EventBase
{
    public $latitude;

    public $longitude;

    public $precision;

    protected function initialize(): void
    {
        $this->latitude = (float) $this->get('latitude');
        $this->longitude = (float) $this->get('longitude');
        $this->precision = (float) $this->get('precision');
    }
}
