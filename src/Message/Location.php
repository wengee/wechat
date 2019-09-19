<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-19 10:09:12 +0800
 */
namespace fwkit\Wechat\Message;

class Location extends MessageBase
{
    protected $properties = [
        'latitude',
        'longitude',
        'scale',
        'label',
    ];

    public $latitude;

    public $longitude;

    public $scale;

    public $label;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'location_x' => 'latitude',
            'location_y' => 'longitude',
        ]);
    }
}
