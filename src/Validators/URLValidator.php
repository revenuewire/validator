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
        $urlToValidate = !empty($options['addProtocol']) ? preg_match('/^http[s]?\:\/\//', $data) ? $data : 'http://' . $data : $data;
        if (!$this->validateString($data, $key, $options)) {
            return false;
        }

        if (!filter_var($urlToValidate, FILTER_VALIDATE_URL)) {
            $this->addValidateResult($key,"Invalid URL format", $options);
            return false;
        }

        return true;
    }
}