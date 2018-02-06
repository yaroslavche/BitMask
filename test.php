<?php

Error_Reporting(-1);
ini_set('display_errors', true);

use BitMask\BitMask;
use BitMask\IndexedBitMask;
use BitMask\AssociativeBitMask;
use BitMask\Util\Bits;

$loader = require __DIR__ . '/vendor/autoload.php';

$bm = new AssociativeBitMask(['k1', 'k2', 'k3'], 7);
// dump($bm);
