<?php
namespace Corley\Service;

class Cache
{
    private $caches = [];

    public function set($name, $value)
    {
        if (!$value) {
            return;
        }

        $this->caches[$name] = $value;
        return $value;
    }

    public function get($name)
    {
        if (array_key_exists($name, $this->caches)) {
            return $this->caches[$name];
        }
    }
}
