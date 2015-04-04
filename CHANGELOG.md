# CHANGELOG

## 0.2.0

### Features

- Introduced a new `AbstractDelegateHydrator` class to allow for implementing a hydrator while using a delegate callback
    - While this facilitates simple method delegation, its real design was to allow for the use of type-hinted hydrators
    that could circumvent PHP's type-system limitations.
    - For more info, read the class doc-block of the new `AbstractDelegateHydrator` class
