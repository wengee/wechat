<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 15:57:21 +0800
 */
namespace fwkit\Wechat\Message\Reply;

use fwkit\Wechat\Utils\ErrorCode;
use fwkit\Wechat\Utils\Helper;

abstract class ReplyBase implements ReplyInterface
{
    protected $cryptor;

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
        } elseif (property_exists($this, $key)) {
            $this->{$key} = $value;
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
        $template = $this->template();
        $vars = $this->getVars();

        $search = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, array_keys($vars));
        $replace = array_values($vars);
        $originXml = str_replace($search, $replace, $template);

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
        $vars = get_object_vars($this);
        $vars['createTime'] = time();
        return $vars;
    }

    abstract protected function template(): string;
}
