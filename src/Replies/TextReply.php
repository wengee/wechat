<?php

namespace Kphp\Wechat\Replies;

class TextReply extends ReplyBase
{
    public static $template = '<xml>
        <ToUserName><![CDATA[{target}]]></ToUserName>
        <FromUserName><![CDATA[{source}]]></FromUserName>
        <CreateTime>{time}</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[{content}]]></Content>
    </xml>';
}
