<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class LocationSelect extends EventBase
{
    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    public $poiName;

    protected function initialize(): void
    {
        $this->latitude = (float) $this->get('sendLocationInfo.location_x');
        $this->longitude = (float) $this->get('sendLocationInfo.location_y');
        $this->scale = (int) $this->get('sendLocationInfo.scale');
        $this->label = $this->get('sendLocationInfo.label');
        $this->poiName = $this->get('sendLocationInfo.poiName');
    }
}
