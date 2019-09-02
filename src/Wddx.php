<?php

namespace fize\xml;

/**
 * Class Wddx
 * @package fize\xml
 */
class Wddx
{

    /**
     * 当前wddx上下文
     * @var resource
     */
    private $packet = null;


    public function __construct($comment = null)
    {
        $this->packetStart($comment);
    }

    /**
     * 开始一个wddx上下文，并返回该句柄
     * @param string $comment 注解
     * @return resource
     */
    public function packetStart($comment = null)
    {
        $this->packet = wddx_packet_start($comment);
        return $this->packet;
    }

    /**
     * 添加变量到当前wddx上下文
     * @param mixed $var_name 变量1
     * @param mixed $_ 可继续添加变量
     * @return bool 成功时返回 TRUE， 或者在失败时返回 FALSE。
     */
    public function addVars($var_name, $_ = null)
    {
        return wddx_add_vars($this->packet, $var_name, $_);
    }

    /**
     * 解析一个wddx资源字符串得到其中的变量
     * @param string $packet wddx资源字符串
     * @return mixed 可能是一个字符串、数字或者数组
     */
    public static function deserialize($packet)
    {
        return wddx_deserialize($packet);
    }

    /**
     * 结束当前wddx封装，并返回对应的字符串
     * @return string
     */
    public function packetEnd()
    {
        return wddx_packet_end($this->packet);
    }

    /**
     * 对一个变量进行序列化，返回对应的wddx字符串
     * @param mixed $var 要序列化的变量
     * @param string $comment 注解
     * @return string 失败时返回false
     */
    public static function serializeValue($var, $comment = null)
    {
        return wddx_serialize_value($var, $comment);
    }

    /**
     * 对变量名进行序列化，返回对应的wddx字符串
     * @param mixed $var_name 变量名或者变量名组成的数组
     * @param mixed $_ 可继续添加变量名
     * @return string 失败时返回false
     */
    public static function serializeVars($var_name, $_ = null)
    {
        return wddx_serialize_vars($var_name, $_);
    }
}