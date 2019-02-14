<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:45:29 +0800
 */
namespace fwkit\Wechat\Message\Event;

class Scan extends EventBase
{
    public $ticket;

    public $scene;

    protected function initialize(array $data)
    {
        $this->setAttributes($data, [
            'eventkey' => 'scene',
        ]);
    }
}
