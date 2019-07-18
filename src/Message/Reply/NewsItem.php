<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:18:00 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class NewsItem implements ReplyInterface
{
    protected $attributes = [
        'title' => '',
        'description' => '',
        'url' => '',
        'picUrl' => '',
    ];

    public function __construct(string $title, string $description, string $url, string $picUrl)
    {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->picUrl = $picUrl;
    }

    public function toXml(): string
    {
        $attributes = $this->attributes;
        return "<item>
                    <Title><![CDATA[{$attributes['title']}]]></Title>
                    <Description><![CDATA[{$attributes['description']}]]></Description>
                    <PicUrl><![CDATA[{$attributes['picUrl']}]]></PicUrl>
                    <Url><![CDATA[{$attributes['url']}]]></Url>
                </item>";
    }
}
