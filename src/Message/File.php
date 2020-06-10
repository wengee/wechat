<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class File extends MessageBase
{
    public $title;

    public $description;

    public $fileKey;

    public $fileMd5;

    public $fileSize = 0;

    protected function initialize(): void
    {
        $this->title = $this->get('title');
        $this->description = $this->get('description');
        $this->fileKey = $this->get('fileKey');
        $this->fileMd5 = $this->get('fileMd5');
        $this->fileSize = (int) $this->get('fileTotalLen');
    }
}
