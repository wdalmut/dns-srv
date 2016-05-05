<?php
namespace Corley\Service;

class Dns
{
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function resolve($name)
    {
        $records = $this->cache->get($name);
        if (!$records) {
            $records = $this->cache->set($name, $this->dnsGetRecord($name));
        }

        return $records;
    }

    protected function dnsGetRecord($name)
    {
        return dns_get_record($name, DNS_SRV);
    }
}
