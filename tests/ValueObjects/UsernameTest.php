<?php

namespace Translation\ValueObjects
{

    use Translation\Exceptions\InvalidUsernameException;

    class UsernameTest extends \PHPUnit_Framework_TestCase
    {
        public function testUsernameCanBeCreated()
        {
            $this->assertEquals('Harry Potter', new Username('Harry Potter'));
        }

        public function testInvalidUsernameThrowsException()
        {
            $invalidUsername = str_repeat('x', 51);
            $this->expectException(InvalidUsernameException::class);
            new Username($invalidUsername);
        }
    }
}
