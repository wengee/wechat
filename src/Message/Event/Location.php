<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:16:13 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Location extends EventBase
{
    public $latitude;

    public $longitude;

    public $precision;

    protected function initialize()
    {
        $this->latitude = (float) $this->get('latitude');
        $this->longitude = (float) $this->get('longitude');
        $this->precision = (float) $this->get('precision');
    }
}
