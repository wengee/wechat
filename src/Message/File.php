<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:19:03 +0800
 */
namespace fwkit\Wechat\Message;

class File extends MessageBase
{
    public $title;

    public $description;

    public $fileKey;

    public $fileMd5;

    public $fileSize = 0;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'filekey'       => 'fileKey',
            'filemd5'       => 'fileMd5',
            'filetotallen'  => 'fileSize',
        ]);
    }
}
