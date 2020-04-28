<?php

namespace fize\xml;

use DOMNode;
use SimpleXMLElement;

/**
 * XML 加载
 */
class SimpleXml
{

    /**
     * 从一个DOMNode对象返回一个对应的SimpleXMLElement对象
     *
     * 参数 `$class_name` :
     *   该类必须是SimpleXMLElement或扩展自SimpleXMLElement。
     * @param DOMNode $node
     * @param string  $class_name 要返回的对象类名
     * @return SimpleXMLElement 根据参数$class_name也可能返回SimpleXMLElement扩展类,错误时返回false
     */
    public static function importDom(DOMNode $node, $class_name = "SimpleXMLElement")
    {
        return simplexml_import_dom($node, $class_name);
    }

    /**
     * 从文件载入xml到一个SimpleXMLElement对象并返回
     *
     * 参数 `$class_name` :
     *   该类必须是SimpleXMLElement或扩展自SimpleXMLElement。
     * @param string $filename   文件路径
     * @param string $class_name string $class_name 要返回的对象类名
     * @param int    $options    可选的LIBXML_*参数常量
     * @param string $ns         命名空间前缀或者URI
     * @param bool   $is_prefix  指定参数$ns是否为命名空间前缀，默认false，即$ns参数是URI
     * @return SimpleXMLElement 根据参数$class_name也可能返回SimpleXMLElement扩展类,错误时返回false
     */
    public static function loadFile($filename, $class_name = "SimpleXMLElement", $options = 0, $ns = "", $is_prefix = false)
    {
        return simplexml_load_file($filename, $class_name, $options, $ns, $is_prefix);
    }

    /**
     * 从xml字符串载入到一个SimpleXMLElement对象并返回
     *
     * 参数 `$class_name` :
     *   该类必须是SimpleXMLElement或扩展自SimpleXMLElement。
     * @param string $data       xml字符串
     * @param string $class_name string $class_name 要返回的对象类名
     * @param int    $options    可选的LIBXML_*参数常量
     * @param string $ns         命名空间前缀或者URI
     * @param bool   $is_prefix  指定参数$ns是否为命名空间前缀
     * @return SimpleXMLElement 根据参数$class_name也可能返回SimpleXMLElement扩展类,错误时返回false
     */
    public static function loadString($data, $class_name = "SimpleXMLElement", $options = 0, $ns = "", $is_prefix = false)
    {
        return simplexml_load_string($data, $class_name, $options, $ns, $is_prefix);
    }
}
