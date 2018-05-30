<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Utils;
use fwkit\Wechat\ComponentBase;

class Media extends ComponentBase
{
    public function upload(string $file, string $type = null)
    {
        if (!file_exists($file)) {
            throw new \Exception('File is not exists.');
        }

        if ($type === null) {
            if (Utils::endsWith($file, ['.amr', '.mp3'], true)) {
                $type = 'voice';
            } elseif (Utils::endsWith($file, '.mp4', true)) {
                $type = 'video';
            } else {
                $type = 'image';
            }
        }

        $res = $this->post('media/upload')
                    ->withQuery(['type' => $type])
                    ->withFile('media', $file)
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function download(string $mediaId, ?string $saveTo = null, bool $isHdVoice = false)
    {
        $url = $isHdVoice ? 'media/get/jssdk' : 'media/get';
        $res = $this->get($url)
                    ->withQuery(['media_id' => $mediaId]);

        if (empty($saveTo)) {
            $json = $res->getJson();
            is_array($json) && $this->throwOfficialError($json);

            return is_array($json) ? $json : $res->getText();
        }

        $res->download($saveTo);
        return true;
    }
}
