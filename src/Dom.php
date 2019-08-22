<?php
namespace fize\xml;

use SimpleXMLElement;
use DOMElement;

/**
 * Dom管理类
 */
class Dom
{

    /**
     * 从SimpleXMLElement对象中得到对应的DOMElement对象
     * @param SimpleXMLElement $node SimpleXMLElement对象
     * @return DOMElement
     */
    public static function importSimplexml(SimpleXMLElement $node)
    {
        return dom_import_simplexml($node);
    }
}
