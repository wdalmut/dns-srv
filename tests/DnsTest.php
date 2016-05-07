<?php
namespace Corley\Service;

use Prophecy\Argument;

class DnsTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveWithCaching()
    {
        $dns = $this->getMockBuilder("Corley\\Service\\Dns")
            ->setMethods(["dnsGetRecord"])
            ->getMock();
        $dns->method('dnsGetRecord')->will($this->returnValue([
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
        ]));

        $entries = $dns->resolve("www.corsi.walterdalmut.com");

        $this->assertCount(1, $entries);
    }
}
