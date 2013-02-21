Echonest API PHP Library
=====================
This is a library to abstract the Echonest API 

Installation
------------
  1. edit `composer.json` file with following contents:

     ```json
     "require": {
        "echonest/echonest": "dev-master"
     }
     ```
  2. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  3. run `php composer.phar install`

Use
---
Configure the service
```php
use Echonest\Service\Echonest;

Echonest::configure($apiKey);
```

Run a query
```php
$response = Echonest::query('artist', 'biographies', array(
    'id' => 'ARH6W4X1187B99274F',
    'results' => '1',
    'start' => '0',
    'license' => 'cc-by-sa'
));
