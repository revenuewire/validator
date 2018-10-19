<?php
declare(strict_types=1);

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
        if (!is_string($data)) {
            $this->addValidateResult($key, sprintf("%s must be a string.", $key), $options);
            return false;
        }

        $aValid = $options['validExceptions'] ?? [];
        $data = str_replace($aValid, '', $data);

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

        if (isset($options['alnum']) && !ctype_alnum($data)) {
            $this->addValidateResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        if (isset($options['alpha']) && !ctype_alpha($data)) {
            $this->addValidateResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        if (isset($options['upper']) && !ctype_upper($data)) {
            $this->addValidateResult($key, sprintf("%s must be all upper case.", $data, $key), $options);
            return false;
        }

        if (isset($options['lower']) && !ctype_lower($data)) {
            $this->addValidateResult($key, sprintf("%s must be all lower case.", $data, $key), $options);
            return false;
        }

        return true;
    }
}