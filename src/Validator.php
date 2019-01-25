<?php
namespace RW;

use RW\Validators\AgeValidator;
use RW\Validators\CountryValidator;
use RW\Validators\DateTimeValidator;
use RW\Validators\EmailValidator;
use RW\Validators\NumericValidator;
use RW\Validators\URLValidator;

class Validator
{
    use AgeValidator, EmailValidator, NumericValidator, DateTimeValidator, CountryValidator, URLValidator;

    const TYPE_STRING = "string";
    const TYPE_ARRAY = "array";
    const TYPE_OBJECT = "object";
    const TYPE_INTEGER = "int";
    const TYPE_NUMERIC = "float";
    const TYPE_DATETIME = "datetime";
    const TYPE_URL = "url";
    const TYPE_MIXED = "mixed";

    const TYPE_EMAIL = "email";
    const TYPE_AGE = "age";
    const TYPE_COUNTRY = "country";

    const VALIDATORS = [
        self::TYPE_AGE => "validateAge",
        self::TYPE_EMAIL => "validateEmail",
        self::TYPE_STRING => "validateString",
        self::TYPE_INTEGER => "validateInteger",
        self::TYPE_NUMERIC => "validateNumeric",
        self::TYPE_DATETIME => "validateDatetime",
        self::TYPE_COUNTRY => "validateCountry",
        self::TYPE_URL => "validateURL",
        self::TYPE_MIXED => "validateMixed",
    ];

    public $validateResults = [];
    public $schema = [];
    public $dataStructure = [];
    public $options = [];

    const OPTION_EXCEPTION_ON_FIRST_ERROR = "OPTION_EXCEPTION_ON_FIRST_ERROR";
    const OPTION_EXCEPTION_ON_UNDEFINED_DATA = "OPTION_EXCEPTION_ON_UNDEFINED_DATA";
    const DEFAULT_OPTIONS = [
        self::OPTION_EXCEPTION_ON_FIRST_ERROR => false,
        self::OPTION_EXCEPTION_ON_UNDEFINED_DATA => false,
    ];

    /**
     * Validator constructor.
     * @param array $schema
     */
    public function __construct(array $schema = [], array $options = [])
    {
        $this->schema = $schema;
        if (isset($schema['type']) && $schema['type'] === self::TYPE_OBJECT) {
            $this->getSchemaDataStructure($schema, $this->dataStructure);
            $this->dataStructure = $this->dataStructure[$schema['key']]; //strip the root element
        }
        $this->options = array_replace(self::DEFAULT_OPTIONS, $options);
    }

    /**
     * @param array $schema
     * @param array $dataStructure
     */
    public function getSchemaDataStructure(array $schema = [], array &$dataStructure = [])
    {
        $type = $schema['type'] ?? Validator::TYPE_STRING;
        $key = $schema['key'] ?? 0;
        if ($type === Validator::TYPE_OBJECT) {
            $dataStructure[$key] = [];
            foreach ($schema['schema'] as $schemaItem) {
                $this->getSchemaDataStructure($schemaItem, $dataStructure[$key]);
            }
        } else if ($type == Validator::TYPE_ARRAY) {
            $dataStructure[$key] = [];
            $this->getSchemaDataStructure($schema['schema'], $dataStructure[$key]);
        } else {
            $dataStructure[$key] = true;
        }
    }

    /**
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function arrayDiffKeyRecursive (array $arr1, array $arr2)
    {
        $diff = array_diff_key($arr1, $arr2);
        $intersect = array_intersect_key($arr1, $arr2);

        foreach ($intersect as $k => $v) {
            if (is_array($arr1[$k]) && is_array($arr2[$k])) {
                //sneak peak the array, to determine if it is a list or associated array
                if (isset($arr1[$k][0])) {
                    foreach ($arr1[$k] as $i => $item) {
                        if (is_array($item)) {
                            $d = self::arrayDiffKeyRecursive($item, $arr2[$k][0]);
                            if ($d) {
                                $diff[$k][$i] = $d;
                            }
                        }
                    }
                } else {
                    $d = self::arrayDiffKeyRecursive($arr1[$k], $arr2[$k]);
                    if ($d) {
                        $diff[$k] = $d;
                    }
                }
            }
        }

        return $diff;
    }

    /**
     * validate
     *
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function validate($data)
    {
        if (!empty($this->dataStructure)) {
            $diff = self::arrayDiffKeyRecursive($data, $this->dataStructure);
            if (!empty($diff)) {
                if ($this->options[self::OPTION_EXCEPTION_ON_UNDEFINED_DATA] === true) {
                    throw new \Exception("Undefined data found. (" . json_encode($diff) . ")");
                } else {
                    $this->addValidateResult($this->schema['key'], "Undefined data found", $diff);
                }
            }
        }

        return $this->validating($data, $this->schema);
    }

    /**
     * @param $data
     * @param array $schema
     * @return bool
     */
    private function validating($data, array $schema)
    {
        $type = $schema['type'] ?? Validator::TYPE_STRING;
        $required = $schema['required'] ?? true;
        $key = $schema['key'] ?? "";

        if ($required === true && !isset($data)) {
            $this->addValidateResult($key, "Required value missing");
            return false;
        }

        if ($required === false && !isset($data)) {
            return true;
        }

        if ($type == Validator::TYPE_OBJECT) {
            foreach ($schema['schema'] as $schemaItem) {
                $schemaKey = $schemaItem["key"] ?? null;
                $schemaData = $data[$schemaKey] ?? null;
                $schemaItem['key'] = $key . "." . $schemaItem['key'];
                $this->validating($schemaData, $schemaItem);
            }
        } else if ($type === Validator::TYPE_ARRAY) {
            foreach ($data as $i => $d) {
                $schema['schema']['key'] = sprintf("%s[%s]", $key, $i);
                $this->validating($d, $schema['schema']);
            }
        } else {
            $v = Validator::VALIDATORS[$type];
            return $this->$v($data, $key, $schema['options'] ?? []);
        }

        return (count($this->getValidateResult()) <= 0);
    }

    /**
     * getResult
     * @return array
     */
    public function getValidateResult()
    {
        return $this->validateResults;
    }

    /**
     * clearValidateResult
     */
    public function clearValidateResult()
    {
        $this->validateResults = [];
    }

    /**
     * @param $key
     * @param $error
     * @param array $context
     */
    public function addValidateResult($key, $error, $context = [])
    {
        $this->validateResults[] = [
            "key" => $key,
            "error" => $error,
            "context" => $context,
        ];
    }

    /**
     * validateString
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

    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateMixed($data, $key, array $options = [])
    {
        $data = (string) $data;
        return $this->validateString($data, $key, $options);
    }

    /**
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public function validateInteger($data, $key, array $options = [])
    {
        if (!is_numeric($data)) {
            $this->addValidateResult($key, sprintf("%s must be a number.", $key), $options);
            return false;
        }
        $data = $data + 0;
        if (!is_int($data)) {
            $this->addValidateResult($key, sprintf("%s must be an integer.", $key), $options);
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

        if (isset($options['allowedValues']) && !in_array($data, $options['allowedValues'])) {
            $this->addValidateResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        return true;
    }
}