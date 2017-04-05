<?php

namespace Translation\ValueObjects
{

    use Translation\Exceptions\InvalidPasswordException;

    /**
     * @covers Translation\ValueObjects\Password
     * @uses Translation\Exceptions\InvalidPasswordException
     */
    class PasswordTest extends \PHPUnit_Framework_TestCase
    {
        public function testPasswordCanBeCreatedOnHappyPath()
        {
            $this->assertEquals('123456', new Password('123456'));
        }

        public function testIfPasswordIsNotBigEnoughThrowsException()
        {
            $this->expectException(InvalidPasswordException::class);
            new Password('123');
        }

        public function testIfPasswordIsToBigThrowsException()
        {
            $tooLongPassword = str_repeat('x', 256);
            $this->expectException(InvalidPasswordException::class);
            new Password($tooLongPassword);
        }
    }
}
