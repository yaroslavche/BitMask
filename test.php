<?php

Error_Reporting(-1);
ini_set('display_errors', true);

use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

$loader = require __DIR__ . '/vendor/autoload.php';

final class FilePermissions extends AssociativeBitMask
{
    public function __construct(int $mask = 0)
    {
        parent::__construct(['readable', 'writable', 'executable'], $mask);
    }
}

define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
define('ALL', READ | WRITE | EXECUTE);
$t = new FilePermissions(ALL ^ WRITE);
$t->setBit(WRITE);
$t->writable = false;
dump($t->isReadable(), $t->readable); // true true
dump($t->isWritable(), $t->writable); // false false
dump($t->isExecutable(), $t->executable); // true true
dump(Bits::getSetBits($t->get())); // [1, 4]
dump($t); // storage 5
// fix $t::map[3]. unexpected key
