<?php

namespace tests;

use DOMDocument;
use fize\xml\LibXML;
use fize\xml\SimpleXML;
use PHPUnit\Framework\TestCase;

class TestLibXML extends TestCase
{

    public function testClearErrors()
    {
        LibXML::useInternalErrors(true);  //接管xml错误处理
        $sxe = SimpleXML::loadString('this is not xml');

        if ($sxe === false) {
            echo 'Error while parsing the document';

            $errors = LibXML::getErrors();
            var_dump($errors);

            self::assertNotEmpty($errors);

            LibXML::clearErrors();

            echo '***********************************************************\r\n';

            $errors = LibXML::getErrors();  //由于clearErrors，$errors为空
            var_dump($errors);
            self::assertEmpty($errors);
        }
    }

    public function testDisableEntityLoader()
    {
        LibXML::useInternalErrors(true);  //接管xml错误处理
        LibXML::disableEntityLoader(false);

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXML::getErrors();  //无错
        self::assertEmpty($errors);

        LibXML::disableEntityLoader();  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXML::getErrors();
        var_dump($errors);
        self::assertNotEmpty($errors);
    }

    public function testGetErrors()
    {
        LibXML::useInternalErrors(true);  //接管xml错误处理
        LibXML::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXML::getErrors();
        var_dump($errors);
        self::assertIsArray($errors);
        self::assertNotEmpty($errors);
    }

    public function testGetLastError()
    {
        LibXML::useInternalErrors(true);  //接管xml错误处理
        LibXML::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXML::getLastError();
        var_dump($error);
        self::assertIsObject($error);
    }

    public function testSetExternalEntityLoader()
    {
        $dtd = <<<DTD
<!ELEMENT foo (#PCDATA)>
DTD;

        LibXML::setExternalEntityLoader(function ($public, $system, $context) use($dtd) {
            var_dump($public);
            var_dump($system);
            var_dump($context);
            $f = fopen("php://temp", "r+");
            fwrite($f, $dtd);
            rewind($f);
            return $f;
        });

        LibXML::useInternalErrors(true);  //接管xml错误处理
        LibXML::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXML::getLastError();
        var_dump($error);
        self::assertIsObject($error);
    }

    public function testSetStreamsContext()
    {
        $opts = [
            'http' => [
                'user_agent' => 'PHP libxml agent',
            ]
        ];

        $context = stream_context_create($opts);
        LibXML::setStreamsContext($context);

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->saveXML();

        self::assertTrue(true);
    }

    public function testUseInternalErrors()
    {
        LibXML::useInternalErrors(true);  //接管xml错误处理
        LibXML::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXML::getLastError();
        var_dump($error);
        self::assertIsObject($error);
    }
}
