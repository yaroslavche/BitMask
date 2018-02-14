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

$t = new AssociativeBitMask(['readable', 'writable', 'executable'], ALL);
$t->writable = false;
dump($t->isReadable(), $t->readable); // true true
dump($t->isWritable(), $t->writable); // false false
dump($t->isExecutable(), $t->executable); // true true
dump(Bits::getSetBits($t->get())); // [1, 4]
```
