<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextFileException;

    /**
     * @covers Translation\GetText\PoParserFrench
     * @uses Translation\GetText\GetTextEntry
     */
    class PoParserFrenchTest extends \PHPUnit_Framework_TestCase
    {
        /** @var string */
        private $path;

        /** @var PoParserFrench */
        private $parser;

        /** @var GetTextEntry[] */
        private $entries;

        protected function setUp()
        {
            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId('testing');
            $this->entries = [
                $getTextEntry,
            ];
            $this->path = __DIR__ . '/TestFiles/testMessages.po';
            $this->parser = new PoParserFrench($this->path, $this->entries);

        }

        public function testPoFileCanBeParsed()
        {
            /**
             * @var $result GetTextEntry[]
             */
            $result = $this->parser->parse();
            $this->assertSame('testing', $result[0]->getMsgId());
            $this->assertSame('test', $result[0]->getMsgFrench());
        }

        public function testParserThrowsExceptionIfFileNotFound()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserFrench('/../TestFiles/anyFile.po', $this->entries);
            $parser->parse();
        }

        public function testParserThrowsExceptionIfFileNotDefined()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserFrench('', $this->entries);
            $parser->parse();
        }

        public function testParserThrowsExceptionIfFileIsNotPoFile()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserFrench('/../TestFiles/wrongExtension.txt', $this->entries);
            $parser->parse();
        }

        public function testProcessedTranslationsCanBeRetrieved()
        {
            $this->parser->parse();
            $this->assertEquals(1, $this->parser->getProcessedTranslations());
        }
    }
}
