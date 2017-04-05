<?php

namespace Translation\ValueObjects
{

    use Translation\Exceptions\InvalidEmailException;

    /**
     * @covers Translation\ValueObjects\Email
     * @uses Translation\Exceptions\InvalidEmailException
     */
    class EmailTest extends \PHPUnit_Framework_TestCase
    {
        public function testEmailCanBeCreatedOnHappyPath()
        {
            $emailString = 'foo@bar.com';
            $email = new Email($emailString);
            $this->assertEquals($emailString, $email);
        }

        /**
         * @dataProvider invalidEmailProvider
         * @param $invalidEmail
         */
        public function testEmailThrowsExceptionIfInvalid(string $invalidEmail)
        {
            $this->expectException(InvalidEmailException::class);
            new Email($invalidEmail);
        }

        public function invalidEmailProvider(): array
        {
            return [
                ['invalid'],
                ['invalid@'],
                ['invalid@invalid'],
                ['invalid@.invalid'],
                ['@invalid.ch'],
                [str_repeat('x', 80) . '@unittest.ch']
            ];
        }
    }
}
