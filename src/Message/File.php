<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:12 +0800
 */
namespace fwkit\Wechat\Message;

class File extends MessageBase
{
    public $title;

    public $description;

    public $fileKey;

    public $fileMd5;

    public $fileSize = 0;

    protected function initialize()
    {
        $this->title = $this->get('title');
        $this->description = $this->get('description');
        $this->fileKey = $this->get('fileKey');
        $this->fileMd5 = $this->get('fileMd5');
        $this->fileSize = (int) $this->get('fileTotalLen');
    }
}
