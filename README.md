### Install
```bash
composer require yaroslavche/bitmask
```

### Test
```bash
./vendor/bin/behat
```

### Example

```php
<?php

Error_Reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

$flagNames = ['readable', 'writable', 'executable'];
define('READABLE', 0b1);
define('WRITABLE', 2);
define('EXECUTABLE', 1 << 2);
define('ALL', READABLE | WRITABLE | EXECUTABLE);

$t = new AssociativeBitMask($flagNames, ALL);
$t->writable = false;
echo sprintf('isReadable() => %s, readable => %s', $t->isReadable(), $t->readable), PHP_EOL; // true true
echo sprintf('isWritable() => %s, writable => %s', $t->isWritable(), $t->writable), PHP_EOL; // false false
echo sprintf('isExecutable() => %s, executable => %s', $t->isExecutable(), $t->executable), PHP_EOL; // true true

dump('getSetBits', Bits::getSetBits($t->get())); // [1, 4]
dump('getSetBitsIndexes', Bits::getSetBitsIndexes($t->get())); // [0, 2]
dump($t(EXECUTE)); // true. __invoke
echo json_encode($t, JSON_PRETTY_PRINT), PHP_EOL; // jsonSerialize
```
