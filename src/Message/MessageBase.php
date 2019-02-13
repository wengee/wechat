<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-02 18:02:09 +0800
 */
namespace Wechat\Message;

abstract class MessageBase
{
    protected static $types = [
        'image'   => ImageMessage::class,
        'text'    => TextMessage::class,
    ];

    protected $rawXml;

    protected $data;

    public function __construct(string $rawXml, array $data)
    {
        $this->rawXml = $rawXml;
        $this->data = $data;
    }

    public static function factory(string $message)
    {
        $data = (array) simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = array_change_key_case($data, CASE_LOWER);

        $msgType = strtolower($data['MsgType']);
        $className = static::$types[$msgType] ?? UnknownMessage::class;
        return new $className($message, $data);
    }
}
