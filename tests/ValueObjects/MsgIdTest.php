<?php

namespace Translation\ValueObjects
{

    use Translation\Exceptions\InvalidIdException;

    /**
     * @covers Translation\ValueObjects\MsgId
     */
    class MsgIdTest extends \PHPUnit_Framework_TestCase
    {
        public function testMsgIdCanBeCreatedSuccessfully()
        {
            $id = 'Test Message Id';
            $msgId = new MsgId($id);
            $this->assertSame($id, (string) $msgId);
        }

        public function testMsgIdThrowsExceptionIfItsEmpty()
        {
            $this->expectException(InvalidIdException::class);
            new MsgId('');
        }

        public function testMsgIdThrowsExceptionIfItsTooBig()
        {
            $this->expectException(InvalidIdException::class);
            $tooLongId = str_repeat('x', 256);
            new MsgId($tooLongId);
        }
    }
}
