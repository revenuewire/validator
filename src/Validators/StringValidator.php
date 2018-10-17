<?php
namespace RW\Validators;

/**
 * Trait StringValidator
 * @package RW\Validators
 */
trait StringValidator
{
    /**
     * stringValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateString($data, $key, array $options = [])
    {
        if (isset($options['max']) && mb_strlen($data) > $options['max']) {
            $this->addValidateResult($key, sprintf("%s must be less than %s characters.", $key, $options['max']), $options);
            return false;
        }

        if (isset($options['min']) && mb_strlen($data) < $options['min']) {
            $this->addValidateResult($key, sprintf("%s must be greater than %s characters.", $key, $options['min']), $options);
            return false;
        }

        if (isset($options['allowedValues']) && !in_array($data, $options['allowedValues'])) {
            $this->addValidateResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        return true;
    }
}