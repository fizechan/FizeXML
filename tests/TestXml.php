<?php

use fize\xml\Xml;
use PHPUnit\Framework\TestCase;

class TestXml extends TestCase
{

    public function testUtf8Decode()
    {
        $str0 = '123456你好！';
        $str1 = Xml::utf8Encode($str0);
        $str2 = Xml::utf8Decode($str1);
        self::assertEquals($str0, $str2);
    }

    public function testUtf8Encode()
    {
        $str = '123456你好！';
        $str = Xml::utf8Encode($str);
        var_dump($str);
        self::assertIsString($str);
    }

    public function testErrorString()
    {
        $errstr = Xml::errorString(XML_ERROR_PARTIAL_CHAR);
        var_dump($errstr);
        self::assertIsString($errstr);
    }

    public function testGetCurrentByteIndex()
    {
        //invalid xml file
        $xmlfile = __DIR__ . '/data/invalid.xml';

        $xml = new Xml();
        $xml->parserCreate();
        $fp = fopen($xmlfile, 'r');
        while ($xmldata = fread($fp, 4096))
        {
            if (!$xml->parse($xmldata, feof($fp))){
                $index = $xml->getCurrentByteIndex();
                self::assertNotEmpty($index);
            }
        }
        $xml->parserFree();
    }

    public function testGetCurrentColumnNumber()
    {
        //invalid xml file
        $xmlfile = __DIR__ . '/data/invalid.xml';

        $xml = new Xml();
        $xml->parserCreate();
        $fp = fopen($xmlfile, 'r');
        while ($xmldata = fread($fp, 4096))
        {
            if (!$xml->parse($xmldata, feof($fp))){
                $number = $xml->getCurrentColumnNumber();
                self::assertNotEmpty($number);
            }
        }
        $xml->parserFree();
    }

    public function testGetCurrentLineNumber()
    {
        //invalid xml file
        $xmlfile = __DIR__ . '/data/invalid.xml';

        $xml = new Xml();
        $xml->parserCreate();
        $fp = fopen($xmlfile, 'r');
        while ($xmldata = fread($fp, 4096))
        {
            if (!$xml->parse($xmldata, feof($fp))){
                $line = $xml->getCurrentLineNumber();
                self::assertNotEmpty($line);
            }
        }
        $xml->parserFree();
    }

    public function testGetErrorCode()
    {
        //invalid xml file
        $xmlfile = __DIR__ . '/data/invalid.xml';

        $xml = new Xml();
        $xml->parserCreate();
        $fp = fopen($xmlfile, 'r');
        while ($xmldata = fread($fp, 4096))
        {
            if (!$xml->parse($xmldata, feof($fp))){
                $errcode = $xml->getErrorCode();
                self::assertNotEmpty($errcode);
            }
        }
        $xml->parserFree();
    }

    public function testParseIntoStruct()
    {
        $simple = "<para><note>simple note</note></para>";
        $xml = new Xml();
        $xml->parserCreate();
        $xml->parseIntoStruct($simple, $vals, $index);
        $xml->parserFree();

        echo "Index array\n";
        print_r($index);
        echo "\nVals array\n";
        print_r($vals);

        self::assertIsArray($index);
        self::assertIsArray($vals);
    }

    public function testParse()
    {
        $simple = "<para><note>simple note</note></para>";
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->parse($simple);
        self::assertEquals($result, 1);
    }

    public function testParserCreateNs()
    {
        $xml = new Xml();
        $parser = $xml->parserCreateNs();
        self::assertIsResource($parser);
    }

    public function testParserCreate()
    {
        $xml = new Xml();
        $parser = $xml->parserCreate();
        self::assertIsResource($parser);
    }

    public function testParserFree()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->parserFree();
        self::assertTrue($result);
    }

    public function testParserGetOption()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $opt1 = $xml->parserGetOption(XML_OPTION_TARGET_ENCODING);
        self::assertIsString($opt1);
    }

    public function testParserSetOption()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->parserSetOption(XML_OPTION_TARGET_ENCODING, 'UTF-8');
        self::assertTrue($result);
    }

    public function testSetCharacterDataHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $xml->setCharacterDataHandler(function ($parser, $data) {
            echo "获取的数据：" . $data;
        });

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetDefaultHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $xml->setDefaultHandler(function ($parser, $data) {
            echo "-->：" . $data;
        });

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetElementHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $xml->setElementHandler(
            function ($parser, $name, $attribs) {
                echo "开始：" . $name;
                var_dump($attribs);
            },
            function ($parser, $name) {
                echo "结束：" . $name;
            }
        );

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetEndNamespaceDeclHandler()
    {
        $xml = new Xml();
        $xml->parserCreateNs();
        $result = $xml->setEndNamespaceDeclHandler(
            function ($parser, $prefix) {
                echo "命名空间：" . $prefix;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/test3.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetExternalEntityRefHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setExternalEntityRefHandler(
            function ($parser, $open_entity_names, $base, $system_id, $public_id) {
                echo "open_entity_names：" . $open_entity_names;
                echo "base：" . $base;
                echo "system_id：" . $system_id;
                echo "public_id：" . $public_id;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/test.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetNotationDeclHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setNotationDeclHandler(
            function ($parser, $notation_name, $base, $system_id, $public_id) {
                echo "notation_name：" . $notation_name;
                echo "base：" . $base;
                echo "system_id：" . $system_id;
                echo "public_id：" . $public_id;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetObject()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setObject($xml);
        self::assertTrue($result);
    }

    public function testSetProcessingInstructionHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setProcessingInstructionHandler(
            function ($parser, $target, $data) {
                echo "target：" . $target;
                echo "data：" . $data;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetStartNamespaceDeclHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setStartNamespaceDeclHandler(
            function ($parser, $prefix, $uri) {
                echo "prefix：" . $prefix;
                echo "uri：" . $uri;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }

    public function testSetUnparsedEntityDeclHandler()
    {
        $xml = new Xml();
        $xml->parserCreate();
        $result = $xml->setUnparsedEntityDeclHandler(
            function ($parser, $entity_name, $base, $system_id, $public_id, $notation_name) {
                echo "entity_name：" . $entity_name;
                echo "base：" . $base;
                echo "system_id：" . $system_id;
                echo "public_id：" . $public_id;
                echo "notation_name：" . $notation_name;
            }
        );

        self::assertTrue($result);

        $fp = fopen(__DIR__ . "/data/rss.xml", "r");
        while ($data = fread($fp, 4096))
        {
            $xml->parse($data, feof($fp));
        }
        $xml->parserFree();

        self::assertTrue(true);
    }
}
