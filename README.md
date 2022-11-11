[![PHP build](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml/badge.svg)](https://github.com/yaroslavche/BitMask/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/yaroslavche/bitmask/branch/main/graph/badge.svg)](https://codecov.io/gh/yaroslavche/bitmask)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/yaroslavche/BitMask/main)](https://infection.github.io)
[![PHP](http://poser.pugx.org/yaroslavche/bitmask/require/php)](https://packagist.org/packages/yaroslavche/bitmask)
# BitMask

PHP library for working with bitmask values

## Getting Started
Usually enough for checking bits: 
```php
define('READ', 1 << 0);
define('WRITE', 1 << 1);
define('EXECUTE', 1 << 2);
$mask = READ | WRITE | EXECUTE;
echo sprintf('read: %d write: %d execute: %d mask: %d', READ, WRITE, EXECUTE, $mask);
// read: 1 write: 2 execute: 4 mask: 7
if ($mask & READ) {
    // $mask have a READ
}
```

But you can try other way with this package:

```php
use BitMask\BitMask;
use BitMask\Util\Bits;

$bitmask = new BitMask();
$bitmask->set(READ, WRITE, EXECUTE);

$integerMask = $bitmask->get(); // int 7
$boolIsSetBit = $bitmask->has(READ, WRITE, EXECUTE); // bool true

// get some info about bits
$integerMostSignificantBit = Bits::getMostSignificantBit($bitmask->get()); // int 2
$arraySetBits = Bits::getSetBits($bitmask->get()); // array:3 [1, 2, 4]
$arraySetBitsIndexes = Bits::getSetBitsIndexes($bitmask->get()); // array:3 [0, 1, 2]
$string = Bits::toString($bitmask->get()); // string "111"

// some helpers
$integerBit = Bits::indexToBit(16); // int 65536
$integerIndex = Bits::bitToIndex(65536); // int 16
$boolIsSingleBit = Bits::isSingleBit(8); // true

// change mask 
$bitmask->remove(EXECUTE);
Bits::getSetBits($bitmask->get()); // array:3 [1, 2]

$bitmask->setBits(0b1000); // throws OutOfRangeException
```

Some examples can be found in [BitMaskInterface](/src/BitMaskInterface.php) and in [tests](/tests)

`EnumBitMask` is extended BitMask

```php
enum Permissions {
    case Read;
    case Write;
    case Execute;
}

use BitMask\EnumBitMask;

$bitmask = new EnumBitMask(Permissions::class);
$bitmask->setEnumBits(Permissions::Read, Permissions::Execute);
$bitmask->isSetEnumBits(Permissions::Read); // true
$bitmask->isSetEnumBits(Permissions::Write); // false
$bitmask->setEnumBits(Permissions::Write);
$bitmask->isSetEnumBits(Permissions::Read, Permissions::Write, Permissions::Execute); // true
$bitmask->unsetEnumBits(Permissions::Write);
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
$ ./vendor/bin/phpstan analyse src/ -c phpstan.neon --level=9 --no-progress -vvv --memory-limit=1024M
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
