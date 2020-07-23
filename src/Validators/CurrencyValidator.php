<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait CurrencyValidator
 * @package RW\Validators
 */
trait CurrencyValidator
{
    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function validateCurrency($data, $key, array $options = [])
    {
        foreach (CountryValidator::$countries as $country) {
            if (in_array($data, $country['currency'])) {
                return true;
            }
        }
        $this->addValidateResult($key, sprintf("%s is an invalid country.", $key, $data), $options);
        return false;
    }
}