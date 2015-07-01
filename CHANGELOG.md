# CHANGELOG

## 0.2.1

### Bug fixes

- Updated a test to work with PHP 7's new engine exceptions, in particular the `TypeError` exception class
- Fixed a bug with how the `StructureFactory` detected map-like structures with mixed key types


## 0.2.0

### Features

- Introduced a new `AbstractDelegateHydrator` class to allow for implementing a hydrator while using a delegate callback
    - While this facilitates simple method delegation, its real design was to allow for the use of type-hinted hydrators
    that could circumvent PHP's type-system limitations.
    - For more info, read the class doc-block of the new `AbstractDelegateHydrator` class
