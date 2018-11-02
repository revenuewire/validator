<?php

/**
 * Class validateAgeTest
 */
class validateDatetimeTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "abc", [], false ],
            [ "1983-01-02", [], true ],
            [ "01/02/83", [], true ],
            [ 32, [], false ],
            [ 1541174503, [], false ],
            [ "1541174503", [], false ],
            [ "01/02/83", ["dateFormat" => "Ymd"], false ],
            [ "1983-01-02", ["dateFormat" => "Ymd"], false ],
            [ "1983-01-02", ["dateFormat" => "Y-m-d"], true ],
            [ "1983-01-02 12:32:23", [], true ],
            [ "01/02/83 12:32", [], true ],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testDatetime($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateDatetime($input, "test-date-time", $options));
    }
}