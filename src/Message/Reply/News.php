<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 14:22:14 +0800
 */
namespace fwkit\Wechat\Message\Reply;

class News extends ReplyBase
{
    protected $articles = [];

    public function addItem(string $title, string $description = '', string $url = '', string $picUrl = '')
    {
        if (count($this->articles) >= 8) {
            throw new \Exception('Too many articles.');
        }

        $this->articles[] = new NewsItem($title, $description, $url, $picUrl);
        return $this;
    }

    protected function getVars(): array
    {
        $vars = parent::getVars();
        $vars['count'] = count($this->articles);

        $articleItems = '';
        foreach ($this->articles as $article) {
            $articleItems .= $article->toXml();
        }
        $vars['articleItems'] = $articleItems;

        return $vars;
    }

    protected function template(): string
    {
        return '<xml>
                    <ToUserName><![CDATA[{{openId}}]]></ToUserName>
                    <FromUserName><![CDATA[{{accountId}}]]></FromUserName>
                    <CreateTime>{{createTime}}</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>{{count}}</ArticleCount>
                    <Articles>{{articleItems}}</Articles>
                </xml>';
    }
}
