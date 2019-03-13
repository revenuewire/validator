<?php
declare(strict_types=1);

namespace RW\Validators;

/**
 * Trait URLValidator
 * @package RW\Validators
 */
trait URLValidator
{
    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateURL($data, $key, array $options = [])
    {
        if (!$this->validateString($data, $key, $options)) {
            return false;
        }

        if(stristr($data, 'http://') === FALSE && stristr($data, 'https://') === false) {
            $data = "http://" . $data;
        }

        if (!filter_var($data, FILTER_VALIDATE_URL)) {
            $this->addValidateResult($key,"Invalid URL format", $options);
            return false;
        }

        return true;
    }
}