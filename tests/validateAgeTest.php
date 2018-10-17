<?php

/**
 * Class validateAgeTest
 */
class validateAgeTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "abc", false ],
            [ "1983-01-02", true ],
            [ "01/02/83", true ],
            [ 32, true ],
            [ 16, false ],
            [ 18, true ],
            [ 100, false ],
            [ 99, true ],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testAge($input, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateAge($input, "", ["min" => 18, "max" => 99]));
    }
}