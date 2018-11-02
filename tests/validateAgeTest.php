<?php

/**
 * Class validateAgeTest
 */
class validateAgeTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "abc", ["min" => 18, "max" => 99], false ],
            [ "1983-01-02", ["min" => 18, "max" => 99], true ],
            [ "01/02/83",["min" => 18, "max" => 99],  true ],
            [ 32, ["min" => 18, "max" => 99], true ],
            [ 16, ["min" => 18, "max" => 99], false ],
            [ 18, ["min" => 18, "max" => 99], true ],
            [ 100, ["min" => 18, "max" => 99], false ],
            [ 99, ["min" => 18, "max" => 99], true ],
            [ "1983-01-02", ["min" => 18, "max" => 99, "dateFormat" => "Ymd"], false ],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testAge($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateAge($input, "validate-age", $options));
    }
}