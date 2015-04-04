# Incoming

[![Build Status](https://img.shields.io/travis/Rican7/incoming.svg?style=flat)](https://travis-ci.org/Rican7/incoming)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Rican7/incoming.svg?style=flat)](https://scrutinizer-ci.com/g/Rican7/incoming/)
[![Quality Score](https://img.shields.io/scrutinizer/g/Rican7/incoming.svg?style=flat)](https://scrutinizer-ci.com/g/Rican7/incoming/)
[![Latest Stable Version](https://img.shields.io/github/release/Rican7/incoming.svg?style=flat)](https://github.com/Rican7/incoming/releases)

Incoming is a PHP library designed to simplify and abstract the transformation of loose, complex input data into
consistent, strongly-typed data structures.

Born out of inspiration from using [Fractal][fractal-lib-website], **Incoming** can be seen as a spiritual inversion.
When working with data models of any kind (database, remote service, etc), it can be a huge pain to take raw input data
and turn it into anything usable. Even worse is when something changes and you have to duplicate code or try and keep
backwards compatibility. Incoming is here to make all this easier while enabling you to create more concern-separated,
reusable, and testable code.

"Wait, what? Why not just use 'x' or 'y'?" [Don't worry, I've got you covered.](#wait-what-why-not-just-use-x-or-y)


## Features

 - Input filtering and transforming
 - Built-in powerful, immutable data-structures for handling complex input
 - Allows for automatic hydrator-for-model resolution via factory abstraction
 - Makes strong use of interfaces for well structured, easily-testable code
 - Completely configurable via composable units

Still curious? Check out the [examples](#examples).


## Installation

1. [Get Composer][composer-website]
2. Add **"incoming/incoming"** to your dependencies: `composer require incoming/incoming`
3. Include the Composer autoloader `<?php require 'vendor/autoload.php';`


## Examples

The easiest example to relate to in the PHP world? "Form" or HTTP request data:

```php
class UserHydrator implements Incoming\Hydrator\HydratorInterface
{
    public function hydrate($input, $model)
    {
        $model->setName($input['name']);
        $model->setGender($input['gender']);
        $model->setFavoriteColor($input['favorite_color']);

        return $model;
    }
}

// Create our incoming processor
$incoming = new Incoming\Processor();

// Process our raw form/request input into a User model
$user = $incoming->process(
    $_POST,            // Our HTTP form-data array
    new User(),        // Our model to hydrate
    new UserHydrator() // The hydrator above
);

// Validate and save the user
// ...
```

Sure, that's a pretty contrived example. But what kind of power can we gain when we compose some pieces together?

```php
class BlogPostHydrator implements Incoming\Hydrator\HydratorInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function hydrate($input, $model)
    {
        $model->setBody($input['body']);
        $model->setCategories($input['categories']);
        $model->setTags($input['tags']);

        // Only allow admin users to publish posts
        if ($this->user->isAdmin()) {
            $model->setPublished($input['published']);
        }

        return $model;
    }
}

// Create our incoming processor
$incoming = new Incoming\Processor();

$hydrator = new BlogPostHydrator(
    $this->getCurrentUser()      // A user context for the hydrator
);

// Process our raw form/request input to update our BlogPost model
$post = $incoming->process(
    $_POST,                      // Our HTTP form-data array
    BlogPost::find($_GET['id']), // Fetch our blog post to update and pass it in
    $hydrator                    // The hydrator above
);

// Validate and save the blog post
// ...
```

Let's try and filter our input first.

```php
class SpecialCharacterFilterTransformer implements Incoming\Transformer\TransformerInterface
{
    public function transform($input)
    {
        foreach($input as &$string) {
            $string = filter_var($string, FILTER_SANITIZE_STRING);
        }

        return $input;
    }
}

class UserHydrator implements Incoming\Hydrator\HydratorInterface
{
    // Same as previous examples...
}

// Create our incoming processor
$incoming = new Incoming\Processor(
    new SpecialCharacterFilterTransformer()
);

// Process our raw form/request input into a User model
$user = $incoming->process(
    $_POST,            // Our HTTP form-data array
    new User(),        // Our model to hydrate
    new UserHydrator() // The hydrator above
);

// Validate and save the user
// ...
```

Missing type hints? PHP's type-system's restrictions can be circumvented!:

```php
class UserHydrator extends Incoming\Hydrator\AbstractDelegateHydrator
{
    // Boom! Type-hintable arguments!
    // (For more info, see the `AbstractDelegateHydrator` class doc-block)
    public function hydrateModel(Incoming\Structure\Map $input, User $model)
    {
        $model->setName($input['name']);
        // ...

        return $model;
    }
}

// Create our incoming processor
$incoming = new Incoming\Processor();

// Process our raw form/request input into a User model
$user = $incoming->process(
    $_POST,            // Our HTTP form-data array
    new User(),        // Our model to hydrate
    new UserHydrator() // The hydrator above
);

// Validate and save the user
// ...
```


## Wait, what? Why not just use "x" or "y"?

Still not sold on the idea even with the provided [examples](#examples)? You may be thinking...

- "Why not just use `MyModelName::fromArray($data)`?"
- "But my ORM already has a `$model->fill($data)` method..."
- "I don't get it..."

Yea, sure, you could easily just build a model from a raw data array or just pass an array of attributes to a "fill"
method and hope that everything goes well, but there's a few issues with doing that: What happens when you refactor a
model or an underlying database table? Do you all of a sudden break backwards compatibility in an HTTP API's parameters
just because your table might have changed? What about if you want to prevent certain parameters from being changed in a
conditional manner? Do you just create a massive chunk of `if` statements in a factory method of the model itself?

The idea with using **Incoming** is to separate concerns, create reusable and composable units, and really enrich an
application's ability to create complex entities while providing a convenient API that rids of some of PHP's "gotchas".

After all, "magical" solutions like mass attribute assignment have [had their pitfalls before][rails-gh-5228]. ;)


# License

**Incoming** is proud to be [MIT licensed][license-file].



[fractal-lib-website]: http://fractal.thephpleague.com/
[composer-website]: https://getcomposer.org/
[rails-gh-5228]: https://github.com/rails/rails/issues/5228
[license-file]: LICENSE
