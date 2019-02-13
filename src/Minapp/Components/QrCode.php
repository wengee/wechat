<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-02 16:59:59 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;

class QrCode extends ComponentBase
{
    public function getMinappCode(string $path, int $width = 430, $color = [], bool $transparent = false)
    {
        $color = $this->formatColor($color);
        $res = $this->post('wxa/getwxacode', [
            'json' => [
                'path' => $path,
                'width' => $width,
                'auto_color' => empty($color) ? true : false,
                'line_color' => $color,
                'is_hyaline' => $transparent,
            ],
        ]);

        $this->checkResponse($res, null, false);
        return $res->getBody();
    }

    public function getSceneCode(string $scene, string $page, int $width = 430, $color = [], bool $transparent = false)
    {
        $color = $this->formatColor($color);
        $res = $this->post('wxa/getwxacodeunlimit', [
            'json' => [
                'scene' => $scene,
                'page' => $page,
                'width' => $width,
                'auto_color' => empty($color) ? true : false,
                'line_color' => $color,
                'is_hyaline' => $transparent,
            ],
        ]);

        $this->checkResponse($res, null, false);
        return $res->getBody();
    }

    public function create(string $path, int $width = 430)
    {
        $res = $this->post('cgi-bin/wxaapp/createwxaqrcode', [
            'json' => [
                'path' => $path,
                'width' => $width,
            ],
        ]);

        $this->checkResponse($res, null, false);
        return $res->getBody();
    }

    private function formatColor($color): ?array
    {
        $rgb = [];
        if (is_str($color)) {
            if (strlen($color) === 6) {
                $colorVal = hexdec($hexStr);
                $rgb['r'] = 0xFF & ($colorVal >> 0x10);
                $rgb['g'] = 0xFF & ($colorVal >> 0x8);
                $rgb['b'] = 0xFF & $colorVal;
            } elseif (strlen($color) === 3) {
                $rgb['r'] = hexdec(str_repeat(substr($color, 0, 1), 2));
                $rgb['g'] = hexdec(str_repeat(substr($color, 1, 1), 2));
                $rgb['b'] = hexdec(str_repeat(substr($color, 2, 1), 2));
            }
        } elseif (is_array($color) && count($color) === 3) {
            list($r, $g, $b) = $color;
            $rgb = ['r' => $r, 'g' => $g, 'b' => $b];
        }

        return $rgb ?: null;
    }
}
