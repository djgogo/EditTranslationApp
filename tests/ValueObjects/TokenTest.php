<?php

namespace Translation\ValueObjects
{
    /**
     * @covers Translation\ValueObjects\Token
     */
    class TokenTest extends \PHPUnit_Framework_TestCase
    {
        public function testTokenValueCanBeSet()
        {
            $this->assertEquals('testTokenValue123234', new Token('testTokenValue123234'));
        }

        public function testTokenLengthIsRight()
        {
            $token = new Token();
            $this->assertSame(40, strlen((string)$token));
        }
    }
}
