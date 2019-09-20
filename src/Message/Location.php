<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:26 +0800
 */
namespace fwkit\Wechat\Message;

class Location extends MessageBase
{
    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    protected function initialize()
    {
        $this->latitude = (float) $this->get('location_x');
        $this->longitude = (float) $this->get('location_y');
        $this->scale = (int) $this->get('scale');
        $this->label = $this->get('label');
    }
}
