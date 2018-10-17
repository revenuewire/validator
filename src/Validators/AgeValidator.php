<?php
/**
 * Created by PhpStorm.
 * User: swang
 * Date: 2018-10-17
 * Time: 3:30 PM
 */

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