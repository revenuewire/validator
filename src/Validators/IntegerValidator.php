<?php
/**
 * Created by PhpStorm.
 * User: swang
 * Date: 2018-10-17
 * Time: 3:25 PM
 */

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
        if (!is_int($data)) {
            $this->addValidateResult($key, sprintf("%s must be numeric.", $key), $options);
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

        return true;
    }
}