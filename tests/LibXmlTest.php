<?php
/** @noinspection PhpComposerExtensionStubsInspection */

use PHPUnit\Framework\TestCase;
use fize\xml\LibXml;
use fize\xml\SimpleXml;

class LibXmlTest extends TestCase
{

    public function testClearErrors()
    {
        LibXml::useInternalErrors(true);  //接管xml错误处理
        $sxe = SimpleXml::loadString('this is not xml');

        if ($sxe === false) {
            echo 'Error while parsing the document';

            $errors = LibXml::getErrors();
            var_dump($errors);

            self::assertNotEmpty($errors);

            LibXml::clearErrors();

            echo '***********************************************************\r\n';

            $errors = LibXml::getErrors();  //由于clearErrors，$errors为空
            var_dump($errors);
            self::assertEmpty($errors);
        }
    }

    public function testDisableEntityLoader()
    {
        LibXml::useInternalErrors(true);  //接管xml错误处理
        LibXml::disableEntityLoader(false);

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXml::getErrors();  //无错
        self::assertEmpty($errors);

        LibXml::disableEntityLoader();  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXml::getErrors();
        var_dump($errors);
        self::assertNotEmpty($errors);
    }

    public function testGetErrors()
    {
        LibXml::useInternalErrors(true);  //接管xml错误处理
        LibXml::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $errors = LibXml::getErrors();
        var_dump($errors);
        self::assertIsArray($errors);
        self::assertNotEmpty($errors);
    }

    public function testGetLastError()
    {
        LibXml::useInternalErrors(true);  //接管xml错误处理
        LibXml::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXml::getLastError();
        var_dump($error);
        self::assertIsObject($error);
    }

    public function testSetExternalEntityLoader()
    {
        $dtd = <<<DTD
<!ELEMENT foo (#PCDATA)>
DTD;

        LibXml::setExternalEntityLoader(function ($public, $system, $context) use($dtd) {
            var_dump($public);
            var_dump($system);
            var_dump($context);
            $f = fopen("php://temp", "r+");
            fwrite($f, $dtd);
            rewind($f);
            return $f;
        });

        LibXml::useInternalErrors(true);  //接管xml错误处理
        LibXml::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXml::getLastError();
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
        LibXml::setStreamsContext($context);

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->saveXML();

        self::assertTrue(true);
    }

    public function testUseInternalErrors()
    {
        LibXml::useInternalErrors(true);  //接管xml错误处理
        LibXml::disableEntityLoader(true);  //禁用后将报错

        $dom = new DOMDocument('1.0');
        $dom->load(__DIR__ . '/data/test2.xml');
        echo $dom->textContent;

        $error = LibXml::getLastError();
        var_dump($error);
        self::assertIsObject($error);
    }
}
