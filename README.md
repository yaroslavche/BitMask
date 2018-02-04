```php
$bm1 = new BitMask\BitMask();
$bm1->setBit(0b10000);
$bm1->setBit(1 << 3);
$bm1->setBit(4);
$bm1->setBit(1 << 1);
$bm1->setBit(0b1);

$bm2 = new BitMask\BitMask(1 << 0 | 1 << 1 | 1 << 2 | 1 << 3 | 1 << 4);
$bm3 = BitMask\BitMask::init(0b11111);

dump($bm1, $bm2, $bm3, $bm1->get() === $bm2->get() && $bm2->get() === $bm3->get());

```

```
BitMask\BitMask {#7
  #storage: 31
}
BitMask\BitMask {#10
  #storage: 31
}
BitMask\BitMask {#9
  #storage: 31

}
true
```

example

```php
use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
define('ALL', READ | WRITE | EXECUTE);

final class FilePermissions extends AssociativeBitMask
{
    public function __construct(int $mask = 0)
    {
        parent::__construct(['readable', 'writable', 'executable'], $mask);
    }
}

$t = new FilePermissions(ALL ^ WRITE);
dump($t->isReadable(), $t->readable); // true true
dump($t->isWritable(), $t->writable); // false false
dump($t->isExecutable(), $t->executable); // true true
dump(Bits::getSetBits($t->get())); // [1, 4]
```
