<?php
/** @noinspection PhpComposerExtensionStubsInspection */


namespace fize\xml;

use LibXMLError;

/**
 *  LibXML扩展
 * @package fize\xml
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
     * 禁用/启用加载外部实体(如DOM、XMLWriter、XMLReader)的能力。
     * @param bool $disable 为true时禁用，默认为true
     * @return bool 返回设置前的值
     */
    public static function disableEntityLoader($disable = true)
    {
        return libxml_disable_entity_loader($disable);
    }

    /**
     * 返回所有的错误组成的数组
     * @return array
     */
    public static function getErrors()
    {
        return libxml_get_errors();
    }

    /**
     * 返回最后一个LibXMLError错误对象
     * @return LibXMLError
     */
    public static function getLastError()
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
     * 设置下一个libxml文档装入或写入的流上下文
     * @param resource $streams_context 使用stream_context_create()创建的上下文
     */
    public static function setStreamsContext($streams_context)
    {
        libxml_set_streams_context($streams_context);
    }

    /**
     * 禁用libxml错误，并允许用户根据需要获取错误信息
     * @param bool $use_errors 禁用或启用用户错误句柄
     * @return bool 设置前的上一个值
     */
    public static function useInternalErrors($use_errors = false)
    {
        return libxml_use_internal_errors($use_errors);
    }
}