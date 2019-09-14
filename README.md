# Blacklist server

![blacklist server](https://user-images.githubusercontent.com/10347617/64910710-359f4100-d722-11e9-9cc0-071b06330edf.png)

<p align="center">
    <a href="https://styleci.io/repos/206591611"><img src="https://styleci.io/repos/206591611/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/blacklist-server"><img src="https://img.shields.io/packagist/dt/andrey-helldar/blacklist-server.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/blacklist-server"><img src="https://poser.pugx.org/andrey-helldar/blacklist-server/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/blacklist-server"><img src="https://poser.pugx.org/andrey-helldar/blacklist-server/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/blacklist-server/license?format=flat-square" alt="License" /></a>
</p>


## Content

* [Installation](#installation)
* [Using](#using)
* [License](#license)


## Installation

To get the latest version of Laravel Blacklist Server, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/blacklist-server
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/blacklist-server": "^1.0"
    }
}
```

Now, you can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\BlacklistClient\ServiceProvider"
```


## Using

First look at the [config](src/config/settings.php).

The easiest use is to install package [andrey-helldar/blacklist-client](https://github.com/andrey-helldar/blacklist-client).

But if you are not looking for easy ways, then you can contact the facades directly:

```php
use Helldar\BlacklistServer\Facades\Email;
use Helldar\BlacklistServer\Facades\Host;
use Helldar\BlacklistServer\Facades\Ip;
use Helldar\BlacklistServer\Facades\Phone;

return Email::store('foo@example.com');
return Email::exists('foo@example.com');

return Host::store('http://example.com');
return Host::exists('http://example.com');

return Ip::store('192.168.1.1');
return Ip::exists('192.168.1.1');

return Phone::store('+0 (000) 000-00-00');
return Phone::exists('+0 (000) 000-00-00');
```

However, we recommend using the [client](https://github.com/andrey-helldar/blacklist-client).


## License

This package is released under the [MIT License](LICENSE).
