<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:21:17 +0800
 */
namespace fwkit\Wechat\Message;

class Location extends MessageBase
{
    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    protected function initialize(array $data)
    {
        $this->setAttributes($data, [
            'location_x' => 'latitude',
            'location_y' => 'longitude',
        ]);
    }
}
