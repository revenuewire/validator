<?php


class validateCurrencyTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "USD", [], true ],
            [ "CAD", [], true ],
            [ "EUR", [], true ],
            [ "AED", [], true ],
            [ "CAN", [], false ],
            [ "123", [], false]
        ];
    }

    /**
     * @param $input
     * @param $options
     * @param $expected
     * @throws Exception
     * @dataProvider dataProvider
     */
    public function testCurrency($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateCurrency($input, "validate-country", $options));
    }
}