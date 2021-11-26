<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class TestSimpleXMLElement extends TestCase
{

    public function testImportSimplexml()
    {
        $xml = <<<XML
<xml>
  <ToUserName><![CDATA[toUser]]></ToUserName>
  <FromUserName><![CDATA[fromUser]]></FromUserName>
  <CreateTime>1348831860</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA[this is a test]]></Content>
  <MsgId>1234567890123456</MsgId>
  <Person>
    <Name>ruby</Name>
    <Age>24</Age>
    <Company>
      <Name>company</Name>
    </Company>
  </Person>
</xml>
XML;
        $xml = $this->xmlToArray($xml);
        var_dump($xml);

        self::assertTrue(true);
    }

    //将XML转为array
    protected function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
}
