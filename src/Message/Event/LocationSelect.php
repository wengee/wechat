<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:24:56 +0800
 */
namespace fwkit\Wechat\Message\Event;

class LocationSelect extends EventBase
{
    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    public $poiName;

    protected function initialize()
    {
        $this->latitude = (float) $this->get('sendLocationInfo.location_x');
        $this->longitude = (float) $this->get('sendLocationInfo.location_y');
        $this->scale = (int) $this->get('sendLocationInfo.scale');
        $this->label = $this->get('sendLocationInfo.label');
        $this->poiName = $this->get('sendLocationInfo.poiName');
    }
}
