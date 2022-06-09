<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-09 17:38:43 +0800
 */

namespace fwkit\Wechat\Work\Kf;

class Event extends MessageBase
{
    public $eventType;

    public $scene;

    public $sceneParam;

    public $wechatChannels = [];
}
