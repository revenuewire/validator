<?php
/**
 * Created by PhpStorm.
 * User: swang
 * Date: 2018-10-17
 * Time: 3:26 PM
 */

namespace RW\Validators;

/**
 * Trait EmailValidator
 * @package RW\Validators
 */
trait EmailValidator
{
    use StringValidator;

    public function validateEmail($data, $key, array $options = [])
    {
        if (!$this->validateString($data, $key, $options)) {
            return false;
        }

        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $this->addValidateResult($key,"Invalid email format", $options);
            return false;
        }

        return true;
    }
}