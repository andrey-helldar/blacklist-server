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

After installation, your application will accept incoming requests for the creation and verification of spammers in stop lists. To do this, you can use packet [andrey-helldar/blacklist-client](https://github.com/andrey-helldar/blacklist-client) or simply send a POST or GET request to address `https://<your-site.com>/api/blacklist`, passing the necessary parameters:

| field | required | comment |
|---|---|---|
| type | sometimes | available is: "email", "host", "ip", "phone" |
| value | yes | string |

In order for the server part to be able to add or check spammers on its own, you can install package [andrey-helldar/blacklist-client](https://github.com/andrey-helldar/blacklist-client) on it or go the more complicated way using facades:

```php
use Helldar\BlacklistServer\Facades\Blacklist;

return Blacklist::store('foo@example.com') : Helldar\BlacklistServer\Models\Blacklist
return Blacklist::check('foo@example.com') // `false` if not exists and Helldar\BlacklistCore\Exceptions\BlacklistDetectedException if exists.
return Blacklist::exists('foo@example.com') : bool

return Blacklist::store('http://example.com') : Helldar\BlacklistServer\Models\Blacklist
return Blacklist::check('http://example.com') // `false` if not exists and Helldar\BlacklistCore\Exceptions\BlacklistDetectedException if exists.
return Blacklist::exists('http://example.com') : bool

return Blacklist::store('192.168.1.1') : Helldar\BlacklistServer\Models\Blacklist
return Blacklist::check('192.168.1.1') // `false` if not exists and Helldar\BlacklistCore\Exceptions\BlacklistDetectedException if exists.
return Blacklist::exists('192.168.1.1') : bool

return Blacklist::store('+0 (000) 000-00-00') : Helldar\BlacklistServer\Models\Blacklist
return Blacklist::check('+0 (000) 000-00-00') // `false` if not exists and Helldar\BlacklistCore\Exceptions\BlacklistDetectedException if exists.
return Blacklist::exists('+0 (000) 000-00-00') : bool
```

However, we recommend using the [client](https://github.com/andrey-helldar/blacklist-client).

### store

When sending a POST request to the address of server `https://<your-site>/api/blacklist` with the correct data.
Foe example:
```
POST https://<your-site>/api/blacklist
Content-Type: application/json

{
  "type": "email",
  "value": "foo@example.com"
}
```

It will return a JSON object:
```json
{
  "type": "email",
  "value": "foo@example.com",
  "expired_at": "2024-05-11 16:41:04",
  "created_at": "2019-09-14 11:45:04",
  "updated_at": "2019-09-14 16:41:04"
}
```

If the data being sent is filled incorrectly, the server will return an error with code 400 and the following JSON object:
```json
{
  "error": {
    "code": 400,
    "msg": ["<message of the error>"]
  }
}
```

For example:
```json
{
  "error": {
    "code": 400,
    "msg": ["The type must be one of email, host, phone or ip, null given."]
  }
}
```

### exists

If the requested data is not found in the database, the site will return a 200 code:
```json
"ok"
```

If the requested data is found in the database, the site will return the code 423 (Locked):
```json
{
  "error": {
    "code": 423,
    "msg": ["Checked email foo@example.com was found in our database."]
  }
}
```

If the data being sent is filled incorrectly, the server will return an error with code 400 and the following JSON object.
For example:
```json
{
  "error": {
    "code": 400,
    "msg": ["The value field is required."]
  }
}

{
  "error": {
    "code": 400,
    "msg": ["The type must be one of email, host, phone or ip, null given."]
  }
}
```


## License

This package is released under the [MIT License](LICENSE).
