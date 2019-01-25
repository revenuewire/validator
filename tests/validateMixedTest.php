<?php

/**
 * Class validateAgeTest
 */
class validateMixedTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "test@revenuewire.com", [], true ],
            [ "1983-01-02", [], true ],
            [ "中文", [], true ],
            [ 123, [], true ],
            [ "a", ["min" => 2], false ],
            [ "abc", ["max" => 2], false ],
            [ "abcd", ["min" => 1, "max" => 5], true ],
            [ "A", ["allowedValues" => [ "A", "B", "C"]], true ],
            [ "F", ["allowedValues" => [ "A", "B", "C"]], false ],
            [ "ab-ws", [ "validExceptions" => [ "-", "_" ], "alpha" => true ], true ],
            [ "ab-ws", [ "alpha" => true ], false ],
            [ "ab-ws-123", [ "validExceptions" => [ "-", "_" ], "alnum" => true ], true ],
            [ "ab-ws-123", [ "alnum" => true ], false ],
            [ "中文", [ "alpha" => true ], false ],
            [ "ABC", [ "upper" => true ], true ],
            [ "AbC", [ "upper" => true ], false ],
            [ "abc", [ "lower" => true ], true ],
            [ "aBc", [ "lower" => true ], false ],
            [ "aB1", [ "lower" => true ], false ],
        ];
    }

    /**
     * @param $input
     * @param $options
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testString($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateMixed($input, "test-2", $options));
    }
}