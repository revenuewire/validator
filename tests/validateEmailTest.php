<?php

/**
 * Class validateAgeTest
 */
class validateEmailTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "test@revenuewire.com", true ],
            [ "1983-01-02", false ],
            [ "", false ],
            [ 32, false ],
            [ "aaa@aaa", false ],
            [ "aaa", false ],
            [ "a@a.com", true ],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testEmail($input, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateEmail($input, "", []));
    }
}