<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:24:59 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Video extends ReplyBase
{
    public $mediaId;

    public $title;

    public $description;

    protected function template(): string
    {
        return '<xml>
                    <ToUserName><![CDATA[{{openId}}]]></ToUserName>
                    <FromUserName><![CDATA[{{accountId}}]]></FromUserName>
                    <CreateTime>{{createTime}}</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <Video>
                        <MediaId><![CDATA[{{mediaId}}]]></MediaId>
                        <Title><![CDATA[{{title}}]]></Title>
                        <Description><![CDATA[{{description}}]]></Description>
                    </Video>
                </xml>';
    }
}
