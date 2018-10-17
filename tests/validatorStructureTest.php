<?php
class validatorStructureTest extends \PHPUnit\Framework\TestCase
{
    public function testOK()
    {
        $this->assertSame(true, true);
    }

    public function testSchemaStructure()
    {
        $data = [
            "formDataEmail" => "test@revenuewire.com",
            "formDataAge" => 8,
            "formDataPeople" => [
                [
                    "name" => "A",
                    "position" => "CEO"
                ],
                [
                    "name" => "Hello B",
                    "position" => "CTO"
                ],
            ],
            "formDataKeys" => [
                "secret-a",
                "secret-b",
            ],
            "formDataComplex" => [
                "status" => "OK1",
                "messages" => [
                    [
                        "k" => "k1",
                        "v" => "v1",
                    ],
                    [
                        "k" => "k2",
                        "v" => "",
                    ]
                ]
            ]
        ];

        $schema = [
            "key" => "formData",
            "type" => \RW\Validator::TYPE_OBJECT,
            "required" => true,
            "options" => [],
            "schema" => [
                [
                    "key" => "formDataEmail",
                    "type" => \RW\Validator::TYPE_EMAIL,
                    "required" => true,
                    "options" => [],
                ],
                [
                    "key" => "formDataAge",
                    "type" => \RW\Validator::TYPE_AGE,
                    "required" => true,
                    "options" => [
                        "max" => 99,
                        "min" => 18,
                    ],
                ],
                [
                    "key" => "formDataPeople",
                    "type" => \RW\Validator::TYPE_ARRAY,
                    "required" => true,
                    "options" => [],
                    "schema" => [
                        "type" => \RW\Validator::TYPE_OBJECT,
                        "required" => true,
                        "schema" => [
                            [
                                "key" => "name",
                                "type" => \RW\Validator::TYPE_STRING,
                                "required" => true,
                                "options" => [
                                    "min" => 3,
                                ]
                            ],
                            [
                                "key" => "position",
                                "type" => \RW\Validator::TYPE_STRING,
                                "required" => true,
                                "options" => [
                                    "allowedValues" => ["CEO", "CTO", "COO"]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "key" => "formDataKeys",
                    "type" => \RW\Validator::TYPE_ARRAY,
                    "required" => true,
                    "options" => [],
                    "schema" => [
                        "type" => \RW\Validator::TYPE_STRING,
                        "required" => true,
                        "options" => []
                    ]
                ],
                [
                    "key" => "formDataComplex",
                    "type" => \RW\Validator::TYPE_OBJECT,
                    "required" => true,
                    "schema" => [
                        [
                            "key" => "status",
                            "type" => \RW\Validator::TYPE_STRING,
                            "required" => true,
                            "options" => [
                                "allowedValues" => ["OK", "PENDING", "FAILED"]
                            ]
                        ],
                        [
                            "key" => "messages",
                            "type" => \RW\Validator::TYPE_ARRAY,
                            "required" => false,
                            "schema" => [
                                "type" => \RW\Validator::TYPE_OBJECT,
                                "required" => false,
                                "options" => [],
                                "schema" => [
                                    [
                                        "key" => "k",
                                        "type" => \RW\Validator::TYPE_STRING,
                                        "required" => true,
                                        "options" => []
                                    ],
                                    [
                                        "key" => "v",
                                        "type" => \RW\Validator::TYPE_STRING,
                                        "required" => true,
                                        "options" => []
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $expected = [
            [
                "key" => "formData.formDataAge",
                "error" => "formData.formDataAge must be greater than 18.",
                "context" => [
                    "max" => 99,
                    "min" => 18,
                ]
            ],
            [
                "key" => "formData.formDataPeople[0].name",
                "error" => "formData.formDataPeople[0].name must be greater than 3 characters.",
                "context" => [
                    "min" => 3
                ]
            ],
            [
                "key" => "formData.formDataComplex.status",
                "error" => "OK1 is not allowed value for formData.formDataComplex.status.",
                "context" => [
                    "allowedValues" => [
                        "OK", "PENDING", "FAILED"
                    ]
                ]
            ],
            [
                "key" => "formData.formDataComplex.messages[1].v",
                "error" => "Required value missing",
                "context" => []
            ]
        ];
        $result = \RW\Validator::validate($data, $schema);
        $this->assertSame(false, $result);
        $this->assertEquals($expected, \RW\Validator::getResult());
    }
}