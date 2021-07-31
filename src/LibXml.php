<?php

namespace fize\xml;

use LibXMLError;

/**
 *  LibXML 扩展
 */
class LibXml
{

    /**
     * 清除libxml错误缓冲区。
     */
    public static function clearErrors()
    {
        libxml_clear_errors();
    }

    /**
     * 禁用/启用加载外部实体
     *
     * 外部实体 如DOM、XMLWriter、XMLReader。
     * @param bool $disable 为 true 时禁用
     * @return bool 返回设置前的值
     */
    public static function disableEntityLoader(bool $disable = true): bool
    {
        return libxml_disable_entity_loader($disable);
    }

    /**
     * 返回所有的错误组成的数组
     * @return array
     */
    public static function getErrors(): array
    {
        return libxml_get_errors();
    }

    /**
     * 返回最后一个 LibXMLError 错误对象
     * @return LibXMLError
     */
    public static function getLastError(): LibXMLError
    {
        return libxml_get_last_error();
    }

    /**
     * 更改默认的外部实体加载程序
     * @param callable 实体加载程序回调函数
     */
    public static function setExternalEntityLoader(callable $resolver_function)
    {
        libxml_set_external_entity_loader($resolver_function);
    }

    /**
     * 设置下一个 libxml 文档装入或写入的流上下文
     * @param resource $streams_context 使用 stream_context_create() 创建的上下文
     */
    public static function setStreamsContext($streams_context)
    {
        libxml_set_streams_context($streams_context);
    }

    /**
     * 禁用 libxml 错误，并允许用户根据需要获取错误信息
     * @param bool $use_errors 禁用或启用用户错误句柄
     * @return bool 设置前的上一个值
     */
    public static function useInternalErrors(bool $use_errors): bool
    {
        return libxml_use_internal_errors($use_errors);
    }
}
