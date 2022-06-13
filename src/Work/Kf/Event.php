<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-13 10:53:41 +0800
 */

namespace fwkit\Wechat\Work\Kf;

class Event extends MessageBase
{
    public $eventType;

    public $scene;

    public $sceneParam;

    public $wechatChannels = [];

    public $failMsgId;

    public $failType;
}
