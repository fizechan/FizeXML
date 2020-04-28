<?php

use fize\xml\XmlRpc;
use PHPUnit\Framework\TestCase;

class TestXmlRpc extends TestCase
{

    public function testDecodeRequest()
    {
        $xml = <<<XML
<methodCall>
<methodName>examples.getStateName</methodName>
<params>
<param>
<value>
<i4>41</i4>
</value>
</param>
</params>
</methodCall>
XML;
        $params = XmlRpc::decodeRequest($xml, $method);
        var_dump($method);
        self::assertIsString($method);
        var_dump($params);
        self::assertIsArray($params);
    }

    public function testDecode()
    {
        $xml = <<<XML
<i4>41</i4>
XML;
        $param = XmlRpc::decode($xml);
        var_dump($param);
        self::assertEquals($param, 41);
    }

    public function testEncodeRequest()
    {
        $method = 'myTestRpcFun';
        $params = ['1', 2, 'OK', '真见鬼了', false, null];
        $xml = XmlRpc::encodeRequest($method, $params);
        echo $xml;
        self::assertIsString($xml);
    }

    public function testEncode()
    {
        $value = -110;
        $xml = XmlRpc::encode($value);
        echo $xml;
        self::assertIsString($xml);
    }

    public function testGetType()
    {
        $type = XmlRpc::getType(null);
        self::assertEquals($type, 'base64');

        $type = XmlRpc::getType(false);
        self::assertEquals($type, 'boolean');

        $type = XmlRpc::getType(1);
        self::assertEquals($type, 'int');

        $type = XmlRpc::getType(1.0);
        self::assertEquals($type, 'double');

        $type = XmlRpc::getType('');
        self::assertEquals($type, 'string');

        $type = XmlRpc::getType([]);
        self::assertEquals($type, 'array');

        $type = XmlRpc::getType(new stdClass());
        self::assertEquals($type, 'array');

        $type = XmlRpc::getType(STDIN);
        self::assertEquals($type, 'int');
    }

    public function testIsFault()
    {
        $xml = <<<XML
<methodResponse>
   <fault>
      <value>
         <struct>
            <member>
               <name>faultCode</name>
               <value><int>26</int></value>
            </member>

            <member>
               <name>faultString</name>
               <value><string>No such method!</string></value>
            </member>

         </struct>
      </value>
   </fault>
</methodResponse>
XML;
        $response = XmlRpc::decode($xml);
        self::assertTrue(XmlRpc::isFault($response));
    }

    public function testParseMethodDescriptions()
    {
        $xml = <<<XML
<methodCall>
<methodName>examples.getStateName</methodName>
<params>
<param>
<value>
<i4>41</i4>
</value>
</param>
</params>
</methodCall>
XML;
        $descriptions = XmlRpc::parseMethodDescriptions($xml);
        var_dump($descriptions);
        self::assertIsArray($descriptions);
    }

    public function testAddIntrospectionData()
    {
        $xmlrpc = new XmlRpc();
        $xmlrpc->create();
        $result = $xmlrpc->addIntrospectionData(['描述1', '描述2']);
        var_dump($result);
        self::assertIsInt($result);
    }

    public function testCallMethod()
    {
        function rpc_server_func($method, $params) {
            $parameter = $params[0];
            if ($parameter == "get") {
                $return = 'This data by get method';
            } else {
                $return = 'Not specify method or params';
            }
            return $return;
        }

        $xmlrpc_server = new XmlRpc();
        $xmlrpc_server->create();
        //注册一个服务器端调用的方法rpc_server，实际指向的是rpc_server_func函数
        $xmlrpc_server->registerMethod("rpc_server", "rpc_server_func");

        //接受客户端POST过来的XML数据
        $request = <<<XML
<methodCall>
<methodName>rpc_server</methodName>
<params>
<param>
<value>
<i4>41</i4>
</value>
</param>
</params>
</methodCall>
XML;

        //执行调用客户端的XML请求后获取执行结果
        $xmlrpc_response = $xmlrpc_server->callMethod($request, null);

        //把函数处理后的结果XML进行输出
        //header('Content-Type: text/xml');
        echo $xmlrpc_response;
        //销毁XML-RPC服务器端资源
        $xmlrpc_server->destroy();

        self::assertIsString($xmlrpc_response);
    }

    public function testCreate()
    {
        $xmlrpc_server = new XmlRpc();
        $resource = $xmlrpc_server->create();
        self::assertIsResource($resource);
    }

    public function testDestroy()
    {
        $xmlrpc_server = new XmlRpc();
        $xmlrpc_server->create();
        $result = $xmlrpc_server->destroy();
        var_dump($result);
        self::assertTrue($result);
    }

    public function testRegisterIntrospectionCallback()
    {
        //@todo PHP官方文档不够完善，无法进行测试
        self::assertTrue(true);
    }

    public function testRegisterMethod()
    {
        function rpc_server_func($method, $params) {
            $parameter = $params[0];
            if ($parameter == "get") {
                $return = 'This data by get method';
            } else {
                $return = 'Not specify method or params';
            }
            return $return;
        }

        $xmlrpc_server = new XmlRpc();
        $xmlrpc_server->create();
        //注册一个服务器端调用的方法rpc_server，实际指向的是rpc_server_func函数
        $xmlrpc_server->registerMethod("rpc_server", "rpc_server_func");

        //接受客户端POST过来的XML数据
        $request = <<<XML
<methodCall>
<methodName>rpc_server</methodName>
<params>
<param>
<value>
<i4>41</i4>
</value>
</param>
</params>
</methodCall>
XML;

        //执行调用客户端的XML请求后获取执行结果
        $xmlrpc_response = $xmlrpc_server->callMethod($request, null);

        //把函数处理后的结果XML进行输出
        //header('Content-Type: text/xml');
        echo $xmlrpc_response;
        //销毁XML-RPC服务器端资源
        $xmlrpc_server->destroy();

        self::assertIsString($xmlrpc_response);
    }

    public function testSetType()
    {
        $value = date('Y-m-d H:i:s');
        $result = XmlRpc::setType($value, 'datetime');
        self::assertTrue($result);
    }
}
