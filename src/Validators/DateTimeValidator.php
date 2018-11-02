<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait DateValidator
 * @package RW\Validators
 */
trait DateTimeValidator
{
    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateDatetime($data, $key, array $options = [])
    {
        $timestamp = strtotime((string) $data);
        if ($timestamp === false) {
            $this->addValidateResult($key, sprintf("%s is an invalid datetime string.", $key, $data), $options);
            return false;
        }

        if (isset($options['dateFormat'])) {
            if ($data !== date($options['dateFormat'], $timestamp)) {
                $this->addValidateResult($key, sprintf("%s is an invalid datetime format.", $key, $data), $options);
                return false;
            }
        }

        return true;
    }
}