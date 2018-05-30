<?php

namespace fwkit\Wechat\Replies;

class ArticleItem extends ReplyBase
{
    public static $template = '<item>
        <Title><![CDATA[{title}]]></Title>
        <Description><![CDATA[{description}]]></Description>
        <PicUrl><![CDATA[{picUrl}]]></PicUrl>
        <Url><![CDATA[{url}]]></Url>
    </item>';
}

class ArticleReply extends ReplyBase
{
    public static $template = '<xml>
        <ToUserName><![CDATA[{target}]]></ToUserName>
        <FromUserName><![CDATA[{source}]]></FromUserName>
        <CreateTime>{time}</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>{count}</ArticleCount>
        <Articles>
        {items}
        </Articles>
    </xml>';

    private $articles;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->articles = [];
    }

    public function addItem($item)
    {
        if (count($this->articles) > 8)
            throw new \Exception('Can\'t add more than 10 articles in an ArticleReply', 998);

        $this->articles[] = new ArticleItem($item);
    }

    public function render()
    {
        $items = [];
        foreach ($this->articles as $article) {
            $items[] = $article->render();
        }

        $this->data['items'] = implode("\n", $items);
        $this->data['count'] = count($this->articles);
        return parent::render();
    }
}
