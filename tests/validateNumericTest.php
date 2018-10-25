<?php

/**
 * Class validateFloatTest
 */
class validateNumericTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ 123, [], true ],
            [ 12.3, [], true ],
            [ "abc", [], false ],
            [ "12", [], true ],
            [ "12.32", [], true ],
            [ 12.00, ["min" => 13.21], false ],
            [ 12.23, ["min" => 11.78], true ],
            [ 11.01, ["min" => 11, "max" => 15], true ],
            [ 8.87, ["min" => 11, "max" => 15], false ],
            [ 14.99, ["min" => 11, "max" => 15], true ],
            [ 15.00, ["min" => 11, "max" => 15], true ],
            [ 15.01, ["min" => 11, "max" => 15], false ],
            [7.32, ["allowedValues" => [1, 3, 5, 7.32, 9]], true],
            [8.99, ["allowedValues" => [1, 3, 5, 8.66, 9]], false],
        ];
    }

    /**
     * @param $input
     * @param $options
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testNumeric($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateNumeric($input, "test-float", $options));
    }
}