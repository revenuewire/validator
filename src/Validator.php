<?php
namespace RW;

class Validator
{
    const TYPE_STRING = "string";
    const TYPE_ARRAY = "array";
    const TYPE_OBJECT = "object";

    const TYPE_EMAIL = "email";
    const TYPE_AGE = "age";

    const VALIDATORS = [
        self::TYPE_AGE => "ageValidator",
        self::TYPE_EMAIL => "emailValidator",
        self::TYPE_STRING => "stringValidator",
    ];

    public static $validateResults = [];

    /**
     * validate
     *
     * @param array $data
     * @param array $schema
     *
     * @return bool
     */
    public static function validate($data, array $schema)
    {
        $type = $schema['type'] ?? Validator::TYPE_STRING;
        $required = $schema['required'] ?? true;
        $key = $schema['key'] ?? "";

        if ($required === true && empty($data)) {
            self::addResult($key, "Required value missing");
            return false;
        }

        if ($required === false && empty($data)) {
            return true;
        }

        if ($type == Validator::TYPE_OBJECT) {
            foreach ($schema['schema'] as $schemaItem) {
                $schemaKey = $schemaItem["key"] ?? null;
                $schemaData = $data[$schemaKey] ?? null;
                $schemaItem['key'] = $key . "." . $schemaItem['key'];
                self::validate($schemaData, $schemaItem);
            }
        } else if ($type === Validator::TYPE_ARRAY) {
            foreach ($data as $i => $d) {
                $schema['schema']['key'] = sprintf("%s[%s]", $key, $i);
                self::validate($d, $schema['schema']);
            }
        } else {
            $v = Validator::VALIDATORS[$type];
            return self::$v($data, $key, $schema['options']);
        }

        return (count(self::getResult()) <= 0);
    }

    /**
     * getResult
     * @return array
     */
    public static function getResult()
    {
        return self::$validateResults;
    }

    /**
     * @param $key
     * @param $error
     * @param array $context
     */
    public static function addResult($key, $error, $context = [])
    {
        self::$validateResults[] = [
            "key" => $key,
            "error" => $error,
            "context" => $context,
        ];
    }

    /**
     * ageValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public static function ageValidator($data, $key, array $options = [])
    {
        if (!is_numeric($data)) {
            self::addResult($key, sprintf("%s must be numeric.", $key), $options);
            return false;
        }

        if (isset($options['max']) && $data > $options['max']) {
            self::addResult($key, sprintf("%s must be less than %s.", $key, $options['max']), $options);
            return false;
        }

        if (isset($options['min']) && $data < $options['min']) {
            self::addResult($key, sprintf("%s must be greater than %s.", $key, $options['min']), $options);
            return false;
        }

        return true;
    }

    /**
     * emailValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public static function emailValidator($data, $key, array $options = [])
    {
        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            self::addResult($key,"Invalid email format", $options);
            return false;
        }

        return true;
    }

    /**
     * stringValidator
     *
     * @param $data
     * @param $key
     * @param array $options
     * @return bool
     */
    public static function stringValidator($data, $key, array $options = [])
    {
        if (isset($options['max']) && mb_strlen($data) > $options['max']) {
            self::addResult($key, sprintf("%s must be less than %s characters.", $key, $options['max']), $options);
            return false;
        }

        if (isset($options['min']) && mb_strlen($data) < $options['min']) {
            self::addResult($key, sprintf("%s must be greater than %s characters.", $key, $options['min']), $options);
            return false;
        }

        if (isset($options['allowedValues']) && !in_array($data, $options['allowedValues'])) {
            self::addResult($key, sprintf("%s is not allowed value for %s.", $data, $key), $options);
            return false;
        }

        return true;
    }
}