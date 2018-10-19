[![Build Status](https://travis-ci.org/revenuewire/validator.svg?branch=master)](https://travis-ci.org/revenuewire/validator)
[![Coverage Status](https://coveralls.io/repos/github/revenuewire/validator/badge.svg?branch=master)](https://coveralls.io/github/revenuewire/validator?branch=master)
[![Latest Stable Version](https://poser.pugx.org/revenuewire/validator/v/stable)](https://packagist.org/packages/revenuewire/validator)
[![License](https://poser.pugx.org/revenuewire/validator/license)](https://packagist.org/packages/revenuewire/validator)
[![composer.lock](https://poser.pugx.org/revenuewire/validator/composerlock)](https://packagist.org/packages/revenuewire/validator)

# Quick Start
## Install
```bash
composer require revenuewire/validator
```

## Usages

### Simple Example
```php
//example of testing my age
$validator = new \RW\Validator();
$result = $validator->validateAge(20, "myAge", ["min" => 18, "max" => 99]);
var_dump($result); //true

//same validator, but using birthday
$validator = new \RW\Validator();
$result = $validator->validateAge("1987-01-08", "myAge", ["min" => 18, "max" => 99]);
var_dump($result); //true

$validator = new \RW\Validator();
$result = $validator->validateAge(16, "myAge", ["min" => 18, "max" => 99]);
var_dump($result); //false

/**
*  [
*     "key" => "myAge",
*     "error" => "myAge must be greater than 18.",
*     "contexts" => [
*        "min" => 18, "max" => 99
*     ]
*  ]
*/
var_dump($validator->getValidateResult());
```

## Options
| Options |  Comments  |
|---|---|
| max | If it is string validation, use strlen(). |
| min | If it is string validation, use strlen(). |
| allowedValues | An array of items allowed for a given data |
| validExceptions | An array of characters that always consider valid regardless the type check. For examples, if the validException is set to be ["_", "-"], and combine with alnum, you will have validate against alpha-numeric with exception of underscore and dash |
| alnum | Only alpha-numeric are valid. |
| alpha | Only alphabets are valid. |
| upper | Only upper case alphabets are valid. |
| lower | Only lower case alphabets are valid. |