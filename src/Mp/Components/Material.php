<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Helper;
use fwkit\Wechat\Utils\Items\Article;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Material extends ComponentBase
{
    public function fetch(string $mediaId)
    {
        $res = $this->post('cgi-bin/material/get_material', [
            'json' => [
                'media_id' => $mediaId,
            ],
        ]);

        return $this->checkResponse($res, [
            'news_item' => 'newsItem',
            'thumb_media_id' => 'thumbMediaId',
            'show_cover_pic' => 'showCoverPic',
            'content_source_url' => 'ContentSourceUrl',
            'down_url' => 'downUrl',
        ], false);
    }

    public function upload($file, string $type = 'image', ?string $title = null, ?string $introduction = null)
    {
        if (is_string($file)) {
            $file = fopen($file, 'r');
        }

        if (!($file instanceof StreamInterface)) {
            $file = new Stream($file);
        }

        $multipart = [
            [
                'name' => 'media',
                'contents' => $file,
            ],
        ];

        if ($type === 'video') {
            $multipart[] = [
                'name' => 'description',
                'contents' => json_encode([
                    'title' => $title,
                    'introduction' => $introduction,
                ])
            ];
        }

        $res = $this->post('cgi-bin/material/add_material', [
            'query' => ['type' => $type],
            'multipart' => $multipart,
        ]);

        return $this->checkResponse($res, [
            'media_id' => 'mediaId',
        ]);
    }

    public function uploadImg($file)
    {
        if (is_string($file)) {
            $file = fopen($file, 'r');
        }

        if (!($file instanceof StreamInterface)) {
            $file = new Stream($file);
        }

        return $this->post('cgi-bin/media/uploadimg', [
            'multipart' => [
                [
                    'name' => 'media',
                    'contents' => $file,
                ],
            ],
        ]);
    }

    public function del(string $mediaId)
    {
        $res = $this->post('cgi-bin/material/del_material', [
            'json' => [
                'media_id' => $mediaId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function addNews(array $articles)
    {
        if (Helper::isAssoc($articles)) {
            $articles = [new Article($articles)];
        } else {
            $articles = array_map(function ($item) {
                if ($item instanceof Article) {
                    return $item;
                } elseif (is_array($item)) {
                    return new Article($item);
                } else {
                    return null;
                }
            }, $articles);
        }

        $res = $this->post('cgi-bin/material/add_news', [
            'json' => [
                'articles' => $articles,
            ],
        ]);

        return $this->checkResponse($res, [
            'media_id' => 'mediaId',
        ]);
    }

    public function updateNews(string $mediaId, int $index, $article)
    {
        if (is_array($article)) {
            $article = new Article($article);
        }

        if (!($article instanceof Article)) {
            throw new \Exception('Params not valid');
        }

        $res = $this->post('cgi-bin/material/update_news', [
            'json' => [
                'media_id' => $mediaId,
                'index' => $index,
                'articles' => $article,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function createArticle(array $args): Article
    {
        return new Article($args);
    }

    public function count()
    {
        $res = $this->get('cgi-bin/material/get_materialcount');
        return $this->checkResponse($res, [
            'voice_count' => 'voiceCount',
            'video_count' => 'videoCount',
            'image_count' => 'imageCount',
            'news_count' => 'newsCount',
        ]);
    }

    public function fetchAll(string $type, int $offset = 0, int $count = 20)
    {
        $res = $this->post('cgi-bin/material/batchget_material', [
            'json' => [
                'type' => $type,
                'offset' => $offset,
                'count' => $count,
            ],
        ]);

        return $this->checkResponse($res, [
            'total_count' => 'totalCount',
            'item_count' => 'itemCount',
            'media_id' => 'mediaId',
            'news_item' => 'newsItem',
            'thumb_media_id' => 'thumbMediaId',
            'show_cover_pic' => 'showCoverPic',
            'content_source_url' => 'contentSourceUrl',
            'need_open_comment' => 'openComment',
            'only_fans_can_comment' => 'onlyFansComment',
            'update_time' => 'updated',
        ]);
    }
}
