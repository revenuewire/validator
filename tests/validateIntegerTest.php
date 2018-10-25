<?php

/**
 * Class validateAgeTest
 */
class validateIntegerTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ 123, [], true ],
            [ 12.3, [], false ],
            [ "abc", [], false ],
            [ "12", [], true ],
            [ "12.32", [], false ],
            [ 12, ["min" => 13], false ],
            [ 12, ["min" => 11], true ],
            [ 11, ["min" => 11, "max" => 15], true ],
            [ 8, ["min" => 11, "max" => 15], false ],
            [ 14, ["min" => 11, "max" => 15], true ],
            [ 15, ["min" => 11, "max" => 15], true ],
            [ 16, ["min" => 11, "max" => 15], false ],
            [7, ["allowedValues" => [1, 3, 5, 7, 9]], true],
            [8, ["allowedValues" => [1, 3, 5, 7, 9]], false],
        ];
    }

    /**
     * @param $input
     * @param $options
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testInteger($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateInteger($input, "test-3", $options));
    }
}