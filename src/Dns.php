<?php
namespace Corley\Service;

class Dns
{
    public function resolve($name)
    {
        return $this->dnsGetRecord($name);
    }

    protected function dnsGetRecord($name)
    {
        return dns_get_record($name, DNS_SRV);
    }
}
