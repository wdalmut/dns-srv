<?php
namespace Corley\Service;

use Prophecy\Argument;

class ResolveTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveSingleHost()
    {
        $dns = $this->prepareDns([
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
        ]);
        $resolve = new Resolve($dns);

        $this->assertEquals("www.corsi.walterdalmut.com", $resolve->resolve("test")["host"]);
    }

    public function testResolveMultiHostWithPriorities()
    {
        $dns = $this->prepareDns([
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 2,
				"weight" => 10,
				"port"   => 80,
				"target" => "2.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 4,
				"weight" => 10,
				"port"   => 80,
				"target" => "4.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "1.corsi.walterdalmut.com",
            ],
        ]);
        $resolve = new Resolve($dns);

        $this->assertEquals("1.corsi.walterdalmut.com", $resolve->resolve("test")["target"]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDomains()
    {
        $dns = $this->prepareDns([]);
        $resolve = new Resolve($dns);
        $resolve->resolve("hello");
    }

    public function testResolveAllWithSingleDomain()
    {
        $dns = $this->prepareDns([
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 2,
				"weight" => 10,
				"port"   => 80,
				"target" => "2.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 4,
				"weight" => 10,
				"port"   => 80,
				"target" => "4.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "1.corsi.walterdalmut.com",
            ],
        ]);
        $resolve = new Resolve($dns);
        $resolve = $resolve->resolveAll("test");

        $this->assertCount(1, $resolve);
        $this->assertEquals("1.corsi.walterdalmut.com", $resolve[0]["target"]);
    }

    public function testResolveAllWithMultipleDomains()
    {
        $dns = $this->prepareDns([
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 2,
				"weight" => 10,
				"port"   => 80,
				"target" => "2.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "1.2.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 4,
				"weight" => 10,
				"port"   => 80,
				"target" => "4.corsi.walterdalmut.com",
            ],
            [
				"host"   => "www.corsi.walterdalmut.com",
				"class"  => "IN",
				"ttl"    => 296,
				"type"   => "SRV",
				"pri"    => 1,
				"weight" => 10,
				"port"   => 80,
				"target" => "1.corsi.walterdalmut.com",
            ],
        ]);
        $resolve = new Resolve($dns);
        $resolve = $resolve->resolveAll("test");

        $this->assertCount(2, $resolve);
        $this->assertRegExp("/^1\./i", $resolve[0]["target"]);
    }

    public function benchmarkResolve($b)
    {
        $dns = new Resolve(new Dns());
        for ($i=0; $i<$b->times(); $i++) {
            $dns->resolve("www.corsi.walterdalmut.com");
        }
    }

    private function prepareDns($values)
    {
        $dns = $this->prophesize("Corley\\Service\\Dns");
        $dns->resolve(Argument::Any())->willReturn($values);

        return $dns->reveal();
    }
}
