# Incoming

[![Build Status](https://img.shields.io/travis/Rican7/incoming.svg?style=flat)](https://travis-ci.org/Rican7/incoming)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Rican7/incoming.svg?style=flat)](https://scrutinizer-ci.com/g/Rican7/incoming/)
[![Quality Score](https://img.shields.io/scrutinizer/g/Rican7/incoming.svg?style=flat)](https://scrutinizer-ci.com/g/Rican7/incoming/)
<!-- [![Latest Stable Version](https://img.shields.io/github/release/Rican7/incoming.svg?style=flat)](https://github.com/Rican7/incoming/releases) -->

Incoming is a PHP library designed to simplify and abstract the transformation of loose, complex input data into
consistent, strongly-typed data structures.

Born out of inspiration from using [Fractal][fractal-lib-website], **Incoming** can be seen as a spiritual inversion.
When working with data models of any kind (database, remote service, etc), it can be a huge pain to take raw input data
and turn it into anything usable. Even worse is when something changes and you have to duplicate code or try and keep
backwards compatibility. Incoming is here to make all this easier while enabling you to create more concern-separated,
reusable, and testable code.

Still curious? Check out the [examples](#examples).


## Installation

1. [Get Composer][composer-website]
2. Add **"incoming/incoming"** to your dependencies: `composer require incoming/incoming`
3. Include the Composer autoloader `<?php require 'vendor/autoload.php';`


## Examples

The easiest to example to relate to in a PHP world? "Form" or HTTP request data:

```php
class UserHydrator implements Incoming\Hydrator\HydratorInterface
{
    public function hydrate($input, $model)
    {
        $model->setName($input->get('name'));
        $model->setGender($input->get('gender'));
        $model->setFavoriteColor($input->get('favorite_color'));
    }
}

// Create our incoming processor
$incoming = new Incoming\Processor();

// Process our raw form/request input into a User model
$user = $incoming->process(
    $_POST, // Our HTTP form-data array
    new User(), // Our model to hydrate
    new UserHydrator() // The hydrator above
);

// Validate and save the user
// ...
```



[fractal-lib-website]: http://fractal.thephpleague.com/
[composer-website]: https://getcomposer.org/
