<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 16:33:59 +0800
 */
namespace Wechat;

abstract class ComponentBase
{
    protected $client;

    public function setClient(ClientBase $client)
    {
        $this->client = $client;
    }

    protected function get(string $url, array $options = [], $accessToken = null)
    {
        return $this->request('GET', $url, $options, $accessToken);
    }

    protected function post(string $url, array $options = [], $accessToken = null)
    {
        return $this->request('POST', $url, $options, $accessToken);
    }

    protected function request(string $method, string $url, array $options = [], $accessToken = null)
    {
        $first = true;

        RETRY:
        $res = $this->client->request($method, $url, $options, $first ? $accessToken : true);
        if ($first && ($accessToken !== false) && is_array($res) && isset($res['errcode']) && ($res['errcode'] == 40001 || $res['errcode'] == 42001)) {
            $first = false;
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
                $res = $this->parseMap($res, $map);
            }

            return $this->makeCollection($res);
        }

        if ($strict) {
            throw new OfficialError('An unknown error occurred.');
        }

        return $res;
    }

    protected function parseMap(array $arr, array $map)
    {
        foreach ($map as $key => $value) {
            if (array_key_exists($key, $arr)) {
                $arrValue = $arr[$key];
                if (is_array($arrValue)) {
                    $arrValue = $this->parseMap($arrValue, $map);
                }

                $arr[$value] = $arrValue;
                unset($arr[$key]);
            }
        }

        return $arr;
    }

    protected function makeCollection($arr)
    {
        return new Collection($arr);
    }
}
