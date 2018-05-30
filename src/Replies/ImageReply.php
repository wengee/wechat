<?php

namespace fwkit\Wechat\Replies;

class ImageReply extends ReplyBase
{
    public static $template = '<xml>
        <ToUserName><![CDATA[{target}]]></ToUserName>
        <FromUserName><![CDATA[{source}]]></FromUserName>
        <CreateTime>{time}</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        <Image>
            <MediaId><![CDATA[{mediaId}]]></MediaId>
        </Image>
    </xml>';
}
