<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Reply;

class Video extends ReplyBase
{
    protected $attributes = [
        'mediaId' => '',
        'title' => '',
        'description' => '',
    ];

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
