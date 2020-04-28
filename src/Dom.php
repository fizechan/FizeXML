<?php

namespace fize\xml;

use DOMElement;
use SimpleXMLElement;

/**
 * Dom管理
 * @notice 需要扩展：ext-dom
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
