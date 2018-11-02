<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait AgeValidator
 * @package Validators
 */
trait AgeValidator
{
    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateAge($data, $key, array $options = [])
    {
        $timestamp = strtotime((string) $data);
        if (!is_int($data) && $timestamp !== false) {
            if (isset($options['dateFormat'])) {
                if ($data != date($options['dateFormat'], $timestamp)) {
                    $this->addValidateResult($key, sprintf("%s is an invalid datetime format.", $key, $data), $options);
                    return false;
                }
            }

            $birthday = new \DateTime();
            $birthday->setTimestamp($timestamp);
            $now = new \DateTime();
            $data = (int) $now->diff($birthday)->format("%y");
        }

        if (!$this->validateInteger($data, $key, $options)) {
            return false;
        }

        return true;
    }
}