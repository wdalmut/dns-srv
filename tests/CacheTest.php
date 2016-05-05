<?php
namespace Corley\Service;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testBase()
    {
        $cache = new Cache();

        $this->assertEquals("ok", $cache->set("ok", "ok"));
    }

    public function testCacheExists()
    {
        $cache = new Cache();

        $cache->set("ok", ["ok" => "ok"]);
        $cache = $cache->get("ok");

        $this->assertEquals(["ok" => "ok"], $cache);
    }

    public function testMissingCache()
    {
        $cache = new Cache();

        $this->assertNull($cache->get("missing"));
    }

    public function testNotSaveEmptyValues()
    {
        $cache = new Cache();

        $this->assertNull($cache->set("test", []));
    }

    public function testNotSaveEmptyValuesOnGet()
    {
        $cache = new Cache();

        $cache->set("test", []);
        $cache = $cache->get("test");

        $this->assertNull($cache);
    }
}
