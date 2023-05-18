<?php

namespace Tests;

use DOMDocument;
use Fize\XML\DOM;
use Fize\XML\SimpleXML;
use PHPUnit\Framework\TestCase;

class TestDOM extends TestCase
{

    public function testImportSimplexml()
    {
        $sxe = SimpleXML::loadString('<books><book><title>blah</title></book></books>');
        $dom_sxe = DOM::importSimpleXml($sxe);
        $dom = new DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
        $dom->appendChild($dom_sxe);
        echo $dom->saveXML();
        self::assertTrue(true);
    }
}
