<?php
namespace Corley\Service;

class Resolve
{
    private $dns;

    public function __construct(Dns $dns)
    {
        $this->dns = $dns;
    }

    public function resolve($name)
    {
        $records = $this->dns->resolve($name);

        if (!$records) {
            throw new \InvalidArgumentException("Missing SRV record for: {$name}");
        }

        $records = $this->filterByLowestPriority($records);
        $records = $this->sortByWeights($records);

        return $this->selectRandomly($records);
    }

    private function filterByLowestPriority($records)
    {
        return array_reduce($records, function($carry, $item) {
            if (!$carry) {
                $carry[] = $item;
                return $carry;
            }

            if ($item["pri"] < $carry[0]["pri"]) {
                $carry = [$item];
            }

            if ($item["pri"] == $carry[0]["pri"]) {
                $carry[] = $item;
            }

            return $carry;
        }, []);
    }

    private function sortByWeights($records)
    {
        usort($records, function($a, $b) {
            if ($a["weight"] == $b["weight"]) {
                return 0;
            }

            return ($a["weight"] < $b["weight"]) ? 1 : -1;
        });
        return $records;
    }

    protected function selectRandomly(array $records)
    {
        $sum = 0;
        for ($i=0; $i<12; $i++) {
            $sum += mt_rand(0, mt_getrandmax()) / mt_getrandmax();
        }
        $sum /= 6;

        return $records[round($sum * (count($records)-1))];
    }
}
