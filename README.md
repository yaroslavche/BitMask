![PHP build](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml/badge.svg)
[![codecov](https://codecov.io/gh/yaroslavche/bitmask/branch/main/graph/badge.svg)](https://codecov.io/gh/yaroslavche/bitmask)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/yaroslavche/BitMask/main)](https://infection.github.io)
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
$boolIsSetBit = $bitmask->isSetBitByShiftOffset(2); // true
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
$bitmask->unsetBitByShiftOffset(2);
$bitmask->setBit(8);

BitsUtil::getSetBits($bitmask->get()); // array:3 [1, 2, 8]
```

Also you can see some examples [here](/src/BitMaskInterface.php)

Exists `IndexedBitMask` and `AssociativeBitMask` helper classes:
```php
use BitMask\IndexedBitMask;
use BitMask\AssociativeBitMask;

$indexed = new IndexedBitMask(1 << 1 | 1 << 2);
// Indexes is RTL, starts from 0. Equals to left shift offset
$indexed->getByIndex(2); // true
$indexed->getByIndex(0); // false

$bitmask = new AssociativeBitMask(5, 3, ['readable', 'writable', 'executable']);
$bitmask->getByKey('readable');
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
$ ./vendor/bin/infection --min-msi=100 --min-covered-msi=100
```
#### Benchmarks
```bash
$ composer phpbench
$ ./vendor/bin/phpbench run benchmarks --report=default
```
#### Static analyzer and code style
##### PHPStan
```bash
$ composer phpstan
$ ./vendor/bin/phpstan analyse src/ -c phpstan.neon --level=8 --no-progress -vvv --memory-limit=1024M
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

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
