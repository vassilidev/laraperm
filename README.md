# Create and manage permission and roles for your user !

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vassilidev/laraperm.svg?style=flat-square)](https://packagist.org/packages/vassilidev/laraperm)
[![Total Downloads](https://img.shields.io/packagist/dt/vassilidev/laraperm.svg?style=flat-square)](https://packagist.org/packages/vassilidev/laraperm)

## Installation

You can install the package via composer:

```bash
composer require vassilidev/laraperm
```

You can publish config and run the migrations with:

```bash
php artisan vendor:publish --provider="Vassilidev\Laraperm\LarapermServiceProvider"
```

This is the contents of the published config file:

```php
return [
    'permissions' => [
        'super-admin' => env('LARAPERM_PERMISSION_SUPERADMIN', '*'),
    ]
];
```
## Usage

```php
Permission::create(['name' => 'edit posts']);

$role = Role::create(['name' => 'Publisher']);
$role->givePermissionTo('edit posts');

$user = User::factory()->create();
$publisher = User::factory()->create();

$user->declareAsSuperAdmin();
 $publisher->assignRole('Publisher');

dump($user->isSuperAdmin()); // True
dump($publisher->isSuperAdmin()); // False

dump($user->can('edit posts')); // True
dump($publisher->can('edit posts')); // True
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Vassili JOFFROY](https://github.com/vassilidev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
