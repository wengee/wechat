<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-12-05 16:21:19 +0800
 */

namespace fwkit\Wechat;

use fwkit\Wechat\Minapp\Client as MinappClient;
use fwkit\Wechat\Mp\Client as MpClient;
use fwkit\Wechat\ThirdParty\Client as ThirdPartyClient;
use fwkit\Wechat\Work\Client as WorkClient;

abstract class ComponentBase
{
    /** @var MinappClient|MpClient|ThirdPartyClient|WorkClient */
    protected $client;

    public function setClient(ClientBase $client): void
    {
        $this->client = $client;
    }

    protected function get(string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        return $this->request('GET', $url, $options, $accessToken);
    }

    protected function post(string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        return $this->request('POST', $url, $options, $accessToken);
    }

    protected function request(string $method, string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        $first = null === $accessToken;

        RETRY:
        $res = $this->client->request($method, $url, $options, $accessToken, $dataType);
        if ($first && (false !== $accessToken) && is_array($res) && isset($res['errcode']) && (40001 == $res['errcode'] || 42001 == $res['errcode'])) {
            $first       = false;
            $accessToken = true;

            goto RETRY;
        }

        return $res;
    }

    protected function checkResponse($res, ?array $map = [], bool $strict = true)
    {
        if (is_array($res)) {
            if (!empty($res['errcode'])) {
                throw new OfficialError($res['errmsg'], $res['errcode']);
            }

            if ($map && is_array($map)) {
                $res = $this->transformKeys($res, $map);
            }

            return $this->makeCollection($res);
        }

        if ($strict) {
            throw new OfficialError('An unknown error occurred.');
        }

        return $res;
    }

    protected function transformKeys(array $arr, array $map): array
    {
        $ret = [];
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $value = $this->transformKeys($value, $map);
            }

            if (isset($map[$key])) {
                $newKey       = $map[$key];
                $ret[$newKey] = $value;
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    protected function makeCollection($arr)
    {
        return new Collection($arr);
    }
}
