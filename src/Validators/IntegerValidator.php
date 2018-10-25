<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait IntegerValidator
 * @package RW\Validators
 */
trait IntegerValidator
{
    /**
     * ageValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateInteger($data, $key, array $options = [])
    {
        if (!is_numeric($data)) {
            $this->addValidateResult($key, sprintf("%s must be a number.", $key), $options);
            return false;
        }
        $data = $data + 0;
        if (!is_int($data)) {
            $this->addValidateResult($key, sprintf("%s must be an integer.", $key), $options);
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