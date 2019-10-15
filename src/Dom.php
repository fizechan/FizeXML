<?php
/** @noinspection PhpComposerExtensionStubsInspection */

namespace fize\xml;

use SimpleXMLElement;
use DOMElement;

/**
 * Dom管理类
 * @notice 需要扩展：ext-dom
 * @package fize\xml
 */
class Dom
{

    /**
     * 从SimpleXMLElement对象中得到对应的DOMElement对象
     * @param SimpleXMLElement $node SimpleXMLElement对象
     * @return DOMElement
     */
    public static function importSimpleXml(SimpleXMLElement $node)
    {
        return dom_import_simplexml($node);
    }
}
