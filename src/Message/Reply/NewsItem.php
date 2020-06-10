<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
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
        $this->attributes['title'] = $title;
        $this->attributes['description'] = $description;
        $this->attributes['url'] = $url;
        $this->attributes['picUrl'] = $picUrl;
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

    public function __toString(): string
    {
        return $this->toXml();
    }
}
