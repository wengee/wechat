<?php

namespace Kphp\Wechat\Replies;

class VideoReply extends ReplyBase
{
    public static $template = '<xml>
        <ToUserName><![CDATA[{target}]]></ToUserName>
        <FromUserName><![CDATA[{source}]]></FromUserName>
        <CreateTime>{time}</CreateTime>
        <MsgType><![CDATA[video]]></MsgType>
        <Video>
            <MediaId><![CDATA[{mediaId}]]></MediaId>
            <Title><![CDATA[{title}]]></Title>
            <Description><![CDATA[{description}]]></Description>
        </Video>
    </xml>';
}
