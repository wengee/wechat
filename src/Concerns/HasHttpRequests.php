<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 18:00:30 +0800
 */
namespace fwkit\Wechat\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait HasHttpRequests
{
    public function request(string $method, string $url, array $options, $accessToken = null, $dataType = 'auto')
    {
        static $client;
        if (!isset($client)) {
            $client = new Client;
        }

        $method = strtoupper($method);
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        if ($accessToken !== false) {
            if ($accessToken === true) {
                $accessToken = $this->getAccessToken(true);
            } else {
                $accessToken = $accessToken ?: $this->getAccessToken();
            }

            $options['query'] = $options['query'] ?? [];
            $options['query']['access_token'] = $accessToken;
        }

        $response = $client->request($method, $url, $options);
        return $this->parseResponse($response);
    }

    protected function parseResponse(Response $response, $dataType = 'auto')
    {
        if ($response->getStatusCode() !== 200 || $dataType === 'raw' || empty($dataType)) {
            return $response;
        }

        $res = null;
        $body = trim($response->getBody());
        if ($dataType === 'xml' || ($dataType === 'auto' && $body{0} === '<')) {
            $res = @simplexml_load_string($body);
        } elseif ($dataType === 'json' || $dataType === 'auto') {
            $res = @json_decode($body, true);
        }

        return ($dataType === 'auto') ? ($res ?: $response) : $res;
    }
}
