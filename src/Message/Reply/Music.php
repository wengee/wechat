<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-07-18 18:13:26 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class Music extends ReplyBase
{
    protected $attributes = [
        'title' => '',
        'description' => '',
        'url' => '',
        'hqUrl' => '',
        'thumbMediaId' => '',
    ];

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
