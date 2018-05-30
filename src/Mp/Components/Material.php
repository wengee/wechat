<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Material extends ComponentBase
{
    public function uploadImg(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception('File is not exists.');
        }

        $res = $this->post('media/uploadimg')
                    ->withFile('media', $file)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function addMedia(string $file, string $type = 'image', ?string $title = null, ?string $introduction = null)
    {
        if (!file_exists($file)) {
            throw new \Exception('File is not exists.');
        }

        $res = $this->post('material/add_material')
                    ->withQuery(['type' => $type])
                    ->withFile('media', $file);

        if ($type === 'video') {
            $description = json_encode([
                'title' => $title,
                'introduction' => $introduction,
            ]);

            $res->withBody(['description' => $description]);
        }

        $res = $res->getJson();
        return $this->throwOfficialError($res);
    }

    public function addNews()
    {
    }

    public function updateNews(string $mediaId, int $index = 0, array $data = [])
    {
    }

    public function get(string $mediaId, ?string $saveTo = null)
    {
        $res = $this->post('material/get_material')
                    ->withJson(['media_id' => $mediaId]);

        if (empty($saveTo)) {
            $json = $res->getJson();
            is_array($json) && $this->throwOfficialError($json);

            return is_array($json) ? $json : $res->getText();
        }

        $res->download($saveTo);
        return true;
    }

    public function delete(string $mediaId)
    {
        $res = $this->post('material/del_material')
                    ->withJson(['media_id' => $mediaId])
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function count()
    {
        $res = $this->get('material/get_materialcount')
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function list(string $type = 'news', int $offset = 0, int $count = 20)
    {
        $params = [
            'type' => $type,
            'offset' => $offset,
            'count' => $count,
        ];

        $res = $this->post('material/batchget_material')
                    ->withJson($params)
                    ->getJson();

        return $this->throwOfficialError($res);
    }
}
