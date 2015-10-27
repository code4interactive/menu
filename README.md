# Menu Package for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Circle CI][ico-circle]](https://circleci.com/gh/code4interactive/menu/tree/master)
[![Total Downloads][ico-downloads]][link-downloads]


## Install

Via Composer

``` bash
composer require code4interactive/menu
php artisan vendor:publish --provider="Code4\Menu\MenuServiceProvider"
```

## Usage

``` php

Menu::init();
Menu::get('menu-name')->render();

```

## Testing

``` bash
composer test
```

## Credits

- [:author_name][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/code4interactive/menu.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/code4interactive/menu/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/code4interactive/menu.svg?style=flat-square
[ico-circle]: https://circleci.com/gh/code4interactive/menu/tree/master.svg?style=svg
[ico-downloads]: https://img.shields.io/packagist/dt/code4interactive/menu.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/code4interactive/menu

[link-travis]: https://travis-ci.org/code4interactive/menu
[link-scrutinizer]: https://scrutinizer-ci.com/g/code4interactive/menu/code-structure
[link-downloads]: https://packagist.org/packages/code4interactive/menu
[link-author]: https://github.com/code4interactive
[link-contributors]: ../../contributors

