<?php

namespace Fize\XML;

/**
 * WDDX
 * @deprecated 该扩展在PHP7.4时已被移除，请勿使用
 */
class WDDX
{

    /**
     * @var resource 当前wddx上下文
     */
    private $packet = null;

    /**
     * 初始化，开始一个wddx上下文
     * @param string|null $comment 注解
     */
    public function __construct(string $comment = null)
    {
        $this->packetStart($comment);
    }

    /**
     * 开始一个wddx上下文，并返回该句柄
     * @param string|null $comment 注解
     * @return resource
     */
    public function packetStart(string $comment = null)
    {
        $this->packet = wddx_packet_start($comment);
        return $this->packet;
    }

    /**
     * 添加变量到当前wddx上下文
     * @param mixed $var_name 变量1
     * @param mixed $_        可继续添加变量
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
    public static function deserialize(string $packet)
    {
        return wddx_deserialize($packet);
    }

    /**
     * 结束当前wddx封装，并返回对应的字符串
     * @return string
     */
    public function packetEnd(): string
    {
        return wddx_packet_end($this->packet);
    }

    /**
     * 对一个变量进行序列化，返回对应的wddx字符串
     * @param mixed       $var     要序列化的变量
     * @param string|null $comment 注解
     * @return string 失败时返回false
     */
    public static function serializeValue($var, string $comment = null): string
    {
        return wddx_serialize_value($var, $comment);
    }

    /**
     * 对变量名进行序列化，返回对应的wddx字符串
     * @param mixed $var_name     变量名或者变量名组成的数组
     * @param mixed ...$var_names 变量名或者变量名组成的数组
     * @return string 失败时返回false
     * @todo  由于wddx_serialize_vars直接调用了外部变量，只能直接使用wddx_serialize_vars
     * @since PHP5.6
     */
    public static function serializeVars($var_name, ...$var_names): string
    {
        return wddx_serialize_vars($var_name, ...$var_names);
    }
}
