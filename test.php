<?php

Error_Reporting(-1);
ini_set('display_errors', true);

use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

$loader = require __DIR__ . '/vendor/autoload.php';

define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
define('ALL', READ | WRITE | EXECUTE);

$fp = new AssociativeBitMask(['readable', 'writable', 'executable'], ALL ^ WRITE);
$fp->setBit(WRITE);
$fp->writable = false;
dump(sprintf('Readable. __call: %s, __get: %s isSetBit: %s', $fp->isReadable(), $fp->readable, $fp->isSetBit(READ))); // true true true
dump(sprintf('Writable. __call: %s, __get: %s isSetBit: %s', $fp->isWritable(), $fp->writable, $fp->isSetBit(WRITE))); // false false false
dump(sprintf('Executable. __call: %s, __get: %s isSetBit: %s', $fp->isExecutable(), $fp->executable, $fp->isSetBit(EXECUTE))); // true true true
dump(Bits::getSetBits($fp->get())); // [1, 4]
