<?php

namespace Kphp\Wechat\Replies;

class VoiceReply extends ReplyBase
{
    public static $template = '<xml>
        <ToUserName><![CDATA[{target}]]></ToUserName>
        <FromUserName><![CDATA[{source}]]></FromUserName>
        <CreateTime>{time}</CreateTime>
        <MsgType><![CDATA[voice]]></MsgType>
        <Voice>
            <MediaId><![CDATA[{mediaId}]]></MediaId>
        </Voice>
    </xml>';
}
