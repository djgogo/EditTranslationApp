<?php

namespace Translation\ParameterObjects
{

    /**
     * @covers Translation\ParameterObjects\UserParameterObject
     */
    class UserParameterObjectTest extends \PHPUnit_Framework_TestCase
    {
        /** @var UserParameterObject */
        private $userParameter;

        protected function setUp()
        {
            $this->userParameter = new UserParameterObject(
                'Harry Potter',
                '123456',
                'harry@potter.net'
            );
        }

        /**
         * @dataProvider provideUserValues
         * @param $value
         * @param $method
         */
        public function testValuesCanBeRetrieved(string $value, string $method)
        {
            $this->assertEquals($value, $this->userParameter->$method());
        }

        public function provideUserValues(): array
        {
            return [
                ['Harry Potter', 'getUsername'],
                ['123456', 'getPassword'],
                ['harry@potter.net', 'getEmail']
            ];
        }
    }
}
