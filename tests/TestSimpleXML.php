<?php

namespace Tests;

use DOMDocument;
use Fize\XML\SimpleXML;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class TestSimpleXML extends TestCase
{

    public function testImportDom()
    {
        $dom = new DOMDocument;
        $dom->loadXML('<root><book><title>blah</title></book></root>');
        $s = SimpleXML::importDom($dom);
        var_dump($s);
        self::assertIsObject($s);

        $title = $s->book[0]->title;
        var_dump($title);
        self::assertNotEmpty($title);
    }

    public function testLoadFile()
    {
        $xml = SimpleXML::loadFile(__DIR__ . '/data/rss.xml');
        print_r($xml);
        self::assertInstanceOf(SimpleXMLElement::class, $xml);
    }

    public function testLoadString()
    {
        $string = <<<XML
<?xml version='1.0'?> 
<document>
 <title>Forty What?</title>
 <from>Joe</from>
 <to>Jane</to>
 <body>
  I know that's the answer -- but what's the question?
 </body>
</document>
XML;

        $xml = SimpleXML::loadString($string);
        print_r($xml);
        self::assertInstanceOf(SimpleXMLElement::class, $xml);
    }
}
