<?php

Error_Reporting(-1);
ini_set('display_errors', true);

$loader = require __DIR__ . '/vendor/autoload.php';

$read = 1 << 0;
$write = 1 << 1;
$execute = 1 << 2;

$bm = new BitMask\AssociativeBitMask(['readable', 'writable', 'executable']);

echo "set writable" . PHP_EOL;
$bm->set($write);
echo "set executable" . PHP_EOL;
$bm->set($execute);
echo "set readable" . PHP_EOL;
$bm->set($read);
dump($bm);

echo "readable " . ($bm->getByKey('readable') ? 'true' : 'false') . PHP_EOL;
echo "unset readable" . PHP_EOL;
$bm->unset($read);
echo "readable " . ($bm->isReadable() ? 'true' : 'false') . PHP_EOL . PHP_EOL;

echo "set properties __get" . PHP_EOL;
$bm->readable = false;
$bm->writable = true;
$bm->executable = false;
dump($bm);
