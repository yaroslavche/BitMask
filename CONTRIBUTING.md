## Contributing

Feel free to fork or contribute =)

#### CI build
```shell
$ composer ci:pack
```

#### Tests
##### PHPUnit
```shell
$ composer phpunit
$ ./vendor/bin/phpunit
```
##### Infection
```shell
$ composer infection
$ ./vendor/bin/infection --min-msi=100 --min-covered-msi=100
```
#### Benchmarks
```shell
$ composer phpbench
$ ./vendor/bin/phpbench run benchmarks --report=default
```
#### Static analyzer and code style
##### PHPStan
```shell
$ composer phpstan
$ ./vendor/bin/phpstan analyse src/ -c phpstan.neon --level=9 --no-progress -vvv --memory-limit=1024M
```

##### Psalm
```shell
$ composer psalm
$ ./vendor/bin/psalm
```
##### PHP-CS
###### Code style check
```shell
$ composer phpcs-check
$ ./vendor/bin/php-cs-fixer check --diff

```
###### Code style fix
```shell
$ composer phpcs-fix
$ ./vendor/bin/php-cs-fixer fix
```
