<?php

namespace Fize\XML;

use DOMElement;
use SimpleXMLElement;

/**
 * DOM管理
 */
class DOM
{

    /**
     * 从SimpleXMLElement对象中得到对应的DOMElement对象
     * @param SimpleXMLElement $node SimpleXMLElement对象
     * @return DOMElement
     */
    public static function importSimpleXml(SimpleXMLElement $node): DOMElement
    {
        return dom_import_simplexml($node);
    }
}
