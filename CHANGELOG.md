# CHANGELOG

## 0.3.0

### Features

- Updated the minimum PHP runtime version to 7.0.0
    - Added scalar type declarations to parameters, where possible
    - Added return type declarations, where possible
    - Enabled strict typing on all files
- Introduced a new 'Builder' and type-processor concept, to better support immutable values/models
- Introduced a new 'Context' concept to hydrators, builders, and the process pipeline, to enable more advanced value/model processing in a more functional manner, without having to resort to holding state in a hydrator/builder

### Bug fixes

- Fixed various code style inconsistencies


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
