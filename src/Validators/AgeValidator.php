<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait AgeValidator
 * @package Validators
 */
trait AgeValidator
{
    use IntegerValidator;

    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateAge($data, $key, array $options = [])
    {
        if (!is_int($data) && strtotime($data) !== false) {
            $birthday = new \DateTime();
            $birthday->setTimestamp(strtotime($data));
            $now = new \DateTime();
            $data = (int) $now->diff($birthday)->format("%y");
        }

        if (!$this->validateInteger($data, $key, $options)) {
            return false;
        }

        return true;
    }
}