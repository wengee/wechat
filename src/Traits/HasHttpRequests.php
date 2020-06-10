<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 18:11:05 +0800
 */

namespace fwkit\Wechat\Traits;

use fwkit\Wechat\ClientBase;
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
        if (!preg_match('#^https?://.+#i', $url) && property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        if ($accessToken !== false) {
            if ($accessToken === true) {
                $accessToken = $this->getAccessToken(true);
            } else {
                $accessToken = $accessToken ?: $this->getAccessToken();
            }

            $options['query'] = $options['query'] ?? [];

            if ($this->getType() === ClientBase::TYPE_THIRD_PARTY) {
                $options['query']['component_access_token'] = $accessToken;
            } else {
                $options['query']['access_token'] = $accessToken;
            }
        }

        if (isset($options['withCert'])) {
            $withCert = $options['withCert'];
            unset($options['withCert']);

            if (is_array($withCert)) {
                if (isset($withCert['sslCert']) && isset($withCert['sslKey'])) {
                    $options['cert'] = $withCert['sslCert'];
                    $options['ssl_key'] = $withCert['sslKey'];
                }
            } elseif ($withCert) {
                $options['cert'] = $this->sslCert;
                $options['ssl_key'] = $this->sslKey;
            }
        }

        // 妈妈咪呀，微信不认识 \uxxxx的中文编码
        if (isset($options['json'])) {
            $options['body'] = json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            unset($options['json']);
        }

        $response = $client->request($method, $url, $options);
        return $this->parseResponse($response, $dataType);
    }

    protected function parseResponse(Response $response, $dataType = 'auto')
    {
        if ($dataType === 'raw' || empty($dataType)) {
            return $response;
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception(
                $response->getBody(),
                $response->getStatusCode()
            );
        }

        $res = null;
        $body = trim((string) $response->getBody());
        if ($body) {
            if ($dataType === 'xml' || ($dataType === 'auto' && $body{0} === '<')) {
                $backup = libxml_disable_entity_loader(true);
                $res = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
                $res = $res ? json_decode(json_encode($res), true) : null;

                libxml_disable_entity_loader($backup);
            } elseif ($dataType === 'json' || $dataType === 'auto') {
                $res = @json_decode($body, true);
            }
        }

        return ($dataType === 'auto') ? ($res ?: $response) : $res;
    }
}
