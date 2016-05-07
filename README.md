# Resolve services with SRV records

Use DNS SRV records to resolve services.

 * selects records with minimum priority (as [RFC
2782](https://tools.ietf.org/html/rfc2782))

 * use a RR algorithm over weights in order to select the right
   service (as RFC). The RR uses a standard distribution (mean 0, variance 1).


```php
use Corley\Service\Dns;
use Corley\Service\Resolve;

$dns = new Resolve(new Dns());

$config = $dns->resolve("www.corsi.walterdalmut.com");

echo $config["target"]; // 1.corsi.walterdalmut.com
echo $config["port"];   // 80
echo $config["pri"];    // the min priority (1)
echo $config["weight"]; // the rr weight resource (10)
```

