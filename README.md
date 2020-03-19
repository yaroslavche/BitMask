[![Build Status](https://travis-ci.org/yaroslavche/BitMask.svg?branch=master)](https://travis-ci.org/yaroslavche/BitMask)
[![codecov](https://codecov.io/gh/yaroslavche/bitmask/branch/master/graph/badge.svg)](https://codecov.io/gh/yaroslavche/bitmask)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/yaroslavche/BitMask/master)](https://infection.github.io)

# BitMask

PHP library for working with bitmask values

## Getting Started
Usually enough for checking bits: 
```php
define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
$mask = READ | WRITE | EXECUTE;
// read: 1 write: 2 execute: 4 mask: 7
echo sprintf('read: %d write: %d execute: %d mask: %d', READ, WRITE, EXECUTE, $mask);
if ($mask & READ) {
    // $mask have a READ
}
```

But you can try other way with this package:
```php
use BitMask\BitMask;
use BitMask\Util\Bits as BitsUtil;

$bitmask = new BitMask();
$bitmask->set(0b111); // 7, 1 << 0 | 1 << 1 | 1 << 2

// get value and check if single bit or mask is set 
$integerMask = $bitmask->get(); // int 7
$boolIsSetBit = $bitmask->isSetBit(4); // bool true
$boolIsSetMask = $bitmask->isSet(6); // bool true

// get some info about bits
$integerMostSignificantBit = BitsUtil::getMSB($bitmask->get()); // int 3
$arraySetBits = BitsUtil::getSetBits($bitmask->get()); // array:3 [1, 2, 4]
$arraySetBitsIndexes = BitsUtil::getSetBitsIndexes($bitmask->get()); // array:3 [0, 1, 2]
$string = BitsUtil::toString($bitmask->get()); // string "111"

// some helpers
$integerBit = BitsUtil::indexToBit(16); // int 65536
$integerIndex = BitsUtil::bitToIndex(65536); // int 16
$boolIsSingleBit = BitsUtil::isSingleBit(8); // true

// change mask 
$bitmask->unsetBit(4);
$bitmask->setBit(8);

BitsUtil::getSetBits($bitmask->get()); // array:3 [1, 2, 8]
```

Also exists `IndexedBitMask` and `AssociativeBitMask` helper classes:
```php
$indexed = new BitMask\IndexedBitMask(1 << 1 | 1 << 2);
// Indexes is RTL, starts from 0. Equals to left shift offset
$indexed->getByIndex(2); // true
$indexed->getByIndex(0); // false

$bitmask = new BitMask\AssociativeBitMask(['readable', 'writable', 'executable'], 5);
/** __call */
$boolReadable = $bitmask->isReadable(); // bool true
$boolWritable = $bitmask->isWritable(); // bool false
$boolExecutable = $bitmask->isExecutable(); // bool true
$result = $bitmask->isUnknownKey(); // null
/** __get */
$boolReadable = $bitmask->readable; // bool true
$boolWritable = $bitmask->writable; // bool false
$boolExecutable = $bitmask->executable; // bool true
$result = $bitmask->unknownKey; // null
/** __set */
$bitmask->readable = false;
$bitmask->writable = true;
$bitmask->executable = false;
``` 

## Installing

Install package via [composer](https://getcomposer.org/) 
```bash
composer require yaroslavche/bitmask
```

## Contributing

Feel free to fork or contribute =)

#### Tests
##### PHPUnit
```bash
$ composer phpunit
$ ./vendor/bin/phpunit
```
##### Infection
```bash
$ composer infection
$ ./vendor/bin/infection --min-msi=50 --min-covered-msi=70
```
#### Benchmarks
```bash
$ composer phpbench
$ ./vendor/bin/phpbench run benchmarks --report=default
```
#### Static analyzer, mess detector and code style
##### PHPStan
```bash
$ composer phpstan
$ ./vendor/bin/phpstan analyse src/ -c phpstan.neon --level=7 --no-progress -vvv --memory-limit=1024M
```
##### PHP-CS
###### Code style check
```bash
$ composer phpcs
$ ./vendor/bin/phpcs
```
###### Code style fix
```bash
$ composer phpcbf
$ ./vendor/bin/phpcbf
```
#### Backward Compatibility
```bash
$ composer bccheck
$ ./vendor/bin/roave-backward-compatibility-check
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
