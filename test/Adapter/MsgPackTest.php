<?php

/**
 * @see https://github.com/laminas/laminas-serializer for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Serializer\Adapter;

use Laminas\Serializer;
use Laminas\Serializer\Exception\ExtensionNotLoadedException;
use Laminas\Serializer\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;
use stdClass;

use function extension_loaded;
use function msgpack_serialize;

/**
 * @group      Laminas_Serializer
 * @covers \Laminas\Serializer\Adapter\MsgPack
 */
class MsgPackTest extends TestCase
{
    /** @var Serializer\Adapter\MsgPack */
    private $adapter;

    protected function setUp(): void
    {
        if (! extension_loaded('msgpack')) {
            try {
                new Serializer\Adapter\MsgPack();
                $this->fail(
                    "Laminas\\Serializer\\Adapter\\MsgPack needs missing ext/msgpack but did't throw exception"
                );
            } catch (ExtensionNotLoadedException $e) {
            }
            $this->markTestSkipped('Laminas\\Serializer\\Adapter\\MsgPack needs ext/msgpack');
        }
        $this->adapter = new Serializer\Adapter\MsgPack();
    }

    protected function tearDown(): void
    {
        $this->adapter = null;
    }

    public function testSerializeString()
    {
        $value    = 'test';
        $expected = msgpack_serialize($value);

        $data = $this->adapter->serialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testSerializeFalse()
    {
        $value    = false;
        $expected = msgpack_serialize($value);

        $data = $this->adapter->serialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testSerializeNull()
    {
        $value    = null;
        $expected = msgpack_serialize($value);

        $data = $this->adapter->serialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testSerializeNumeric()
    {
        $value    = 100;
        $expected = msgpack_serialize($value);

        $data = $this->adapter->serialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testSerializeObject()
    {
        $value    = new stdClass();
        $expected = msgpack_serialize($value);

        $data = $this->adapter->serialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeString()
    {
        $expected = 'test';
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeFalse()
    {
        $expected = false;
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeNull()
    {
        $expected = null;
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeNumeric()
    {
        $expected = 100;
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeObject()
    {
        $expected = new stdClass();
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserialize0()
    {
        $expected = 0;
        $value    = msgpack_serialize($expected);

        $data = $this->adapter->unserialize($value);
        $this->assertEquals($expected, $data);
    }

    public function testUnserializeInvalid()
    {
        $value = "\0\1\r\n";
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unserialization failed');
        $this->adapter->unserialize($value);
    }
}
