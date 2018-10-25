<?php
namespace RW;

use RW\Validators\AgeValidator;
use RW\Validators\EmailValidator;

class Validator
{
    use AgeValidator, EmailValidator;

    const TYPE_STRING = "string";
    const TYPE_ARRAY = "array";
    const TYPE_OBJECT = "object";

    const TYPE_EMAIL = "email";
    const TYPE_AGE = "age";

    const VALIDATORS = [
        self::TYPE_AGE => "validateAge",
        self::TYPE_EMAIL => "validateEmail",
        self::TYPE_STRING => "validateString",
    ];

    public $validateResults = [];

    public function __construct()
    {
    }

    /**
     * validate
     *
     * @param array $data
     * @param array $schema
     *
     * @return bool
     */
    public function validate($data, array $schema)
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
                $this->validate($schemaData, $schemaItem);
            }
        } else if ($type === Validator::TYPE_ARRAY) {
            foreach ($data as $i => $d) {
                $schema['schema']['key'] = sprintf("%s[%s]", $key, $i);
                $this->validate($d, $schema['schema']);
            }
        } else {
            $v = Validator::VALIDATORS[$type];
            return $this->$v($data, $key, $schema['options']);
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
}