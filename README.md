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
//Inicjalizacja menu zdefiniowanych w pliku konfiguracyjnym
Menu::init();

//Renderowanie menu w widoku
Menu::get('menu-name')->render();

//Renderowanie menu w customowym widoku
Menu::get('menu-name')->render('custom_view');

//Ustawianie aktywnego elementu za pomocą ścieżki z konfiguracji
Menu::get('menu-name')->setActiveByPath('settings.roles');

//Ustawianie aktywnego elementu za pomocą url (skrypt będzie próbował sam to zrobić)
Menu::get('menu-name')->setActiveByUrl('/settings/roles');

//Podmienianie frazy w url (np. /administration/user/{user_id}
//Trzeci parametr oznacza wyszukiwanie rekurencyjne w potomkach (default: false)
Menu::get('menu-name')->replaceTermInUrl('{user_id}', '12', true);

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

