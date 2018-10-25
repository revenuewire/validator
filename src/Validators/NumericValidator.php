<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait FloatValidator
 * @package RW\Validators
 */
trait NumericValidator
{
    /**
     * validateNumeric
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateNumeric($data, $key, array $options = [])
    {
        if (!is_numeric($data)) {
            $this->addValidateResult($key, sprintf("%s must be a number.", $key), $options);
            return false;
        }

        $scale = $options['scale'] ?? 2;

        if (isset($options['max']) && bccomp((string) $data, (string) $options['max'], $scale) === 1) {
            $this->addValidateResult($key, sprintf("%s must be less than %s.", $key, $options['max']), $options);
            return false;
        }

        if (isset($options['min']) && bccomp((string) $data, (string) $options['min'], $scale) === -1) {
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