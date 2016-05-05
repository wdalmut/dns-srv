<?php
namespace Corley\Service;

use Prophecy\Argument;

class DnsTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveWithCaching()
    {
        $cache = $this->prophesize("Corley\\Service\\Cache");
        $cache->get("www.corsi.walterdalmut.com")->willReturn([
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "www.walterdalmut.com",
            ],
        ])->shouldBeCalledTimes(1);

        $dns = new Dns($cache->reveal());
        $entries = $dns->resolve("www.corsi.walterdalmut.com");

        $this->assertCount(1, $entries);
    }

    public function testResolveWithoutCaching()
    {
        $values = [
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "www.walterdalmut.com",
            ],
        ];
        $cache = $this->prophesize("Corley\\Service\\Cache");
        $cache->get(Argument::Any())->willReturn(null);
        $cache->set("www.corsi.walterdalmut.com", Argument::Any())
            ->willReturn($values)
            ->shouldBeCalledTimes(1);

        $mock = $this->getMockBuilder("Corley\\Service\\Dns")
            ->setMethods(['dnsGetRecord'])
            ->setConstructorArgs([$cache->reveal()])
            ->getMock();

        $mock->method("dnsGetRecord")->will($this->returnValue($values));

        $this->assertEquals("www.corsi.walterdalmut.com", $mock->resolve("www.corsi.walterdalmut.com")[0]["host"]);
    }

    public function testResolveWithoutCachingAnEmptyResult()
    {
        $values = [];
        $cache = $this->prophesize("Corley\\Service\\Cache");
        $cache->get(Argument::Any())->willReturn(null);
        $cache->set("www.corsi.walterdalmut.com", Argument::Any())
            ->willReturn(null)
            ->shouldBeCalledTimes(1);

        $mock = $this->getMockBuilder("Corley\\Service\\Dns")
            ->setMethods(['dnsGetRecord'])
            ->setConstructorArgs([$cache->reveal()])
            ->getMock();

        $mock->method("dnsGetRecord")->will($this->returnValue($values));

        $this->assertNull($mock->resolve("www.corsi.walterdalmut.com"));
    }
}
