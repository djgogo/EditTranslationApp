<?php

namespace Translation\GetText
{
    /**
     * @covers Translation\GetText\GetTextEntry
     */
    class GetTextEntryTest extends \PHPUnit_Framework_TestCase
    {
        /** @var GetTextEntry */
        private $textEntry;

        protected function setUp()
        {
            $this->textEntry = new GetTextEntry();
        }

        public function testMsgIdCanBeSetAndRetrieved()
        {
            $this->textEntry->setMsgId('testId');
            $this->assertSame('testId', $this->textEntry->getMsgId());
        }

        public function testMsgGermanCanBeSetAndRetrieved()
        {
            $this->textEntry->setMsgGerman('German Text');
            $this->assertSame('German Text', $this->textEntry->getMsgGerman());
        }

        public function testMsgFrenchCanBeSetAndRetrieved()
        {
            $this->textEntry->setMsgFrench('French Text');
            $this->assertSame('French Text', $this->textEntry->getMsgFrench());
        }

        public function testCreatedCanBeSetAndRetrieved()
        {
            $dateTime = new \DateTime('2017-02-27 00:00:00');
            $this->textEntry->setCreated($dateTime);
            $this->assertSame($dateTime, $this->textEntry->getCreated());
        }

        public function testUpdatedCanBeSetAndRetrieved()
        {
            $dateTime = new \DateTime('2017-02-27 00:00:00');
            $this->textEntry->setUpdated($dateTime);
            $this->assertSame($dateTime, $this->textEntry->getUpdated());
        }

    }
}
