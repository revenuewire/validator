# Quick Start
## Install
```bash
composer require revenuewire/validator
```

## Usages

### Simple Example
```php
//example 
$validator = new \RW\Validator();
$result = $validator->validateAge(20, "myAge", ["min" => 18, "max" => 99]);
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