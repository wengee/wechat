<?php

namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;

class Qrcode extends ComponentBase
{
    public function getMinappCode(string $path, int $width = 430, array $color = [], bool $transparent = false)
    {
        $color = $this->formatColor($color);
        $data = [
            'path' => $path,
            'width' => $width,
            'auto_color' => empty($color) ? true : false,
            'line_color' => $color,
            'is_hyaline' => $transparent,
        ];

        $res = $this->post('wxa/getwxacode')
                    ->withJson($data)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function getSceneCode(string $scene, string $page, int $width = 430, array $color = [], bool $transparent = false)
    {
        $color = $this->formatColor($color);
        $data = [
            'scene' => $scene,
            'page' => $page,
            'width' => $width,
            'auto_color' => empty($color) ? true : false,
            'line_color' => $color,
            'is_hyaline' => $transparent,
        ];

        $res = $this->post('wxa/getwxacodeunlimit')
                    ->withJson($data)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function create(string $path, int $width = 430)
    {
        $data = [
            'path' => $path,
            'width' => $width,
        ];

        $res = $this->post('cgi-bin/wxaapp/createwxaqrcode')
                    ->withJson($data)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    private function formatColor(array $color): ?array
    {
        if ($color && count($color) === 3) {
            list($r, $g, $b) = $color;
            return ['r' => $r, 'g' => $g, 'b' => $b];
        }

        return null;
    }
}
