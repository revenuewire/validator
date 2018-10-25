<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait FloatValidator
 * @package RW\Validators
 */
trait FloatValidator
{
    /**
     * ageValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateFloat($data, $key, array $options = [])
    {
        if (!is_float($data)) {
            $this->addValidateResult($key, sprintf("%s must be float.", $key), $options);
            return false;
        }

        if (isset($options['max']) && $data > $options['max']) {
            $this->addValidateResult($key, sprintf("%s must be less than %s.", $key, $options['max']), $options);
            return false;
        }

        if (isset($options['min']) && $data < $options['min']) {
            $this->addValidateResult($key, sprintf("%s must be greater than %s.", $key, $options['min']), $options);
            return false;
        }

        if (isset($options['allowedValues']) && !in_array($data, $options['allowedValues'])) {
            $this->addValidateResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        return true;
    }
}