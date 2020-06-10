<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Reply;

use fwkit\Wechat\Utils\ErrorCode;
use fwkit\Wechat\Utils\Helper;

abstract class ReplyBase implements ReplyInterface
{
    protected $cryptor;

    protected $attributes = [];

    protected $directOutput = false;

    public $accountId;

    public $openId;

    public function __construct(string $accountId, string $openId)
    {
        $this->accountId = $accountId;
        $this->openId = $openId;
    }

    public function setCryptor($cryptor)
    {
        $this->cryptor = $cryptor;
        return $this;
    }

    public function set($key, $value)
    {
        $setter = 'set' . ucfirst($key);
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
        } elseif (array_key_exists($key, $this->attributes)) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function assign(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    public function toXml(): string
    {
        $originXml = $this->template();

        if (!$this->directOutput) {
            $vars = $this->getVars();

            $search = array_map(function ($item) {
                return '{{' . $item . '}}';
            }, array_keys($vars));
            $replace = array_values($vars);
            $originXml = str_replace($search, $replace, $originXml);
        }

        if ($this->cryptor) {
            $nonceStr = Helper::createNonceStr();
            $timestamp = time();

            $errcode = $this->cryptor->encrypt(
                $originXml,
                $timestamp,
                $nonceStr,
                $encryptedXml
            );

            if ($errcode === ErrorCode::OK) {
                return $encryptedXml;
            }
        }

        return $originXml;
    }

    public function __toString(): string
    {
        return $this->toXml();
    }

    protected function getVars(): array
    {
        $vars = $this->attributes ?: [];
        $vars['accountId'] = $this->accountId;
        $vars['openId'] = $this->openId;
        $vars['createTime'] = time();
        return $vars;
    }

    abstract protected function template(): string;
}
