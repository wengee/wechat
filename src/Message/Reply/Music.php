<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:24:47 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Music extends ReplyBase
{
    public $title;

    public $description;

    public $url;

    public $hqUrl;

    public $thumbMediaId;

    protected function template(): string
    {
        return '<xml>
                    <ToUserName><![CDATA[{{openId}}]]></ToUserName>
                    <FromUserName><![CDATA[{{accountId}}]]></FromUserName>
                    <CreateTime>{{createTime}}</CreateTime>
                    <MsgType><![CDATA[music]]></MsgType>
                    <Music>
                        <Title><![CDATA[{{title}}]]></Title>
                        <Description><![CDATA[{{description}}]]></Description>
                        <MusicUrl><![CDATA[{{url}}]]></MusicUrl>
                        <HQMusicUrl><![CDATA[{{hqUrl}}]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[{{thumbMediaId}}]]></ThumbMediaId>
                    </Music>
                </xml>';
    }
}
