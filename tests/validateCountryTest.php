<?php

/**
 * Class validateAgeTest
 */
class validateCountryTest extends \PHPUnit\Framework\TestCase
{
    public function dataProvider()
    {
        return [
            [ "abc", [], false ],
            [ "US", [], true ],
            [ "CA", [], true ],
            [ "CA", ["countryFormat" => "alpha3"], false ],
            [ "CAN", ["countryFormat" => "alpha3"], true ],
        ];
    }

    /**
     * @param $input
     * @param $options
     * @param $expected
     * @throws Exception
     * @dataProvider dataProvider
     */
    public function testCountry($input, $options, $expected)
    {
        $validator = new \RW\Validator();
        $this->assertSame($expected, $validator->validateCountry($input, "validate-country", $options));
    }

    /**
     * @throws Exception
     * @expectedException  Exception
     */
    public function testCountryWithInvalidOptions()
    {
        $validator = new \RW\Validator();
        $validator->validateCountry("US", "validate-country", ["countryFormat" => "abc"]);
    }
}