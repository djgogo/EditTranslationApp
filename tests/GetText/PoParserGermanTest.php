<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextFileException;

    /**
     * @covers Translation\GetText\PoParserGerman
     * @uses Translation\GetText\GetTextEntry
     */
    class PoParserGermanTest extends \PHPUnit_Framework_TestCase
    {
        /** @var string */
        private $path;

        /** @var PoParserGerman */
        private $parser;

        protected function setUp()
        {
            $this->path = __DIR__ . '/TestFiles/testMessages.po';
            $this->parser = new PoParserGerman($this->path);
        }

        public function testPoFileCanBeParsed()
        {
            /**
             * @var $result GetTextEntry[]
             */
            $result = $this->parser->parse();
            $this->assertSame('testing', $result[0]->getMsgId());
            $this->assertSame('test', $result[0]->getMsgGerman());
        }

        public function testParserThrowsExceptionIfFileNotFound()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserGerman('/../TestFiles/anyFile.po');
            $parser->parse();
        }

        public function testParserThrowsExceptionIfFileNotDefined()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserGerman('');
            $parser->parse();
        }

        public function testParserThrowsExceptionIfFileIsNotPoFile()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserGerman('/../TestFiles/wrongExtension.txt');
            $parser->parse();
        }

        public function testParserThrowsExceptionIfFileCanNotBeOpened()
        {
            $this->expectException(GetTextFileException::class);

            $parser = new PoParserGerman('');
            $this->assertFalse (@$parser->openFile('/is-not-writeable/file'));
        }

        public function testProcessedTranslationsCanBeRetrieved()
        {
            $this->parser->parse();
            $this->assertEquals(1, $this->parser->getProcessedTranslations());
        }
    }
}
