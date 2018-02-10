<?php

Error_Reporting(-1);
ini_set('display_errors', true);

use BitMask\BitMask;
use BitMask\IndexedBitMask;
use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

$loader = require __DIR__ . '/vendor/autoload.php';

define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
define('ALL', READ | WRITE | EXECUTE);

$t = new AssociativeBitMask(['readable', 'writable', 'executable'], ALL ^ WRITE);
dump($t->isReadable(), $t->readable); // true true
dump($t->isWritable(), $t->writable); // false false
dump($t->isExecutable(), $t->executable); // true true
dump(Bits::getSetBits($t->get())); // [1, 4]
