<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:18:00 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class NewsItem implements ReplyInterface
{
    public $title;

    public $description;

    public $url;

    public $picUrl;

    public function __construct(string $title, string $description, string $url, string $picUrl)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->picUrl = $picUrl;
    }

    public function toXml(): string
    {
        return "<item>
                    <Title><![CDATA[{$this->title}]]></Title>
                    <Description><![CDATA[{$this->description}]]></Description>
                    <PicUrl><![CDATA[{$this->picUrl}]]></PicUrl>
                    <Url><![CDATA[{$this->url}]]></Url>
                </item>";
    }
}
