<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
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
