<?php

namespace Tests;

use Fize\XML\WDDX;
use PHPUnit\Framework\TestCase;

class TestWDDX extends TestCase
{

    public function test__construct()
    {
        new WDDX();
        new WDDX('test');
        self::assertTrue(true);
    }

    public function testPacketStart()
    {
        $wddx = new WDDX();
        $resource = $wddx->packetStart('test');
        self::assertIsResource($resource);
    }

    public function testAddVars()
    {
        //a unix timestamp
        $date    = "1094095513";

        //some data to be included
        $books   = [
            'programming'   => ['php','perl','java'],
            'markup'        => ['UML','XML','HTML']
        ];

        //stick data to an array to iterate over
        $data_to_serialize = [$date,$books];

        //create the packet
        $wddx = new WDDX("SOME DATA ARRAY");

        //loop through the data
        foreach($data_to_serialize as $key => $data)
        {
            //create a var whith the name of the content of $key
            $$key = $data;
            $wddx->addVars($key);
        }

        $wddx_str = $wddx->packetEnd();
        self::assertIsString($wddx_str);
    }

    public function testDeserialize()
    {
        $text = "fize";
        $packet = WDDX::serializeValue($text);
        $header = '<?xml version="1.0" encoding="iso-8859-1"?>';
        $newText = WDDX::deserialize($header . $packet);
        echo "WDDX Packet: $packet\n";
        echo "Deserialized Object: $newText\n";

        self::assertEquals($text, $newText);
    }

    public function testPacketEnd()
    {
        //a unix timestamp
        $date    = "1094095513";

        //some data to be included
        $books   = [
            'programming'   => ['php','perl','java'],
            'markup'        => ['UML','XML','HTML']
        ];

        //stick data to an array to iterate over
        $data_to_serialize = [$date,$books];

        //create the packet
        $wddx = new WDDX("SOME DATA ARRAY");

        //loop through the data
        foreach($data_to_serialize as $key => $data)
        {
            //create a var whith the name of the content of $key
            $$key = $data;
            $wddx->addVars($key);
        }

        $wddx_str = $wddx->packetEnd();
        self::assertIsString($wddx_str);
    }

    public function testSerializeValue()
    {
        $text = "fize";
        $packet = WDDX::serializeValue($text);
        $header = '<?xml version="1.0" encoding="iso-8859-1"?>';
        $newText = WDDX::deserialize($header . $packet);
        echo "WDDX Packet: $packet\n";
        echo "Deserialized Object: $newText\n";

        self::assertEquals($text, $newText);
    }

    public function testSerializeVars()
    {
        $a = 1;
        $b = 5.5;
        $c = ["blue", "orange", "violet"];
        $d = "colors";

        var_dump($a);
        var_dump($b);
        var_dump($c);
        var_dump($d);

        $clvars = ["c", "d"];
        $str1 = WDDX::serializeVars("a", "b", $clvars);
        $str2 = wddx_serialize_vars("a", "b", $clvars);

        self::assertNotEquals($str2, $str1);  //恰恰说明了这个方法是有问题的。
    }
}
