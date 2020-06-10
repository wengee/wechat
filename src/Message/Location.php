<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class Location extends MessageBase
{
    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    protected function initialize(): void
    {
        $this->latitude = (float) $this->get('location_x');
        $this->longitude = (float) $this->get('location_y');
        $this->scale = (int) $this->get('scale');
        $this->label = $this->get('label');
    }
}
