<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextImportException;

    /**
     * @covers Translation\GetText\PoToMySqlImporter
     * @uses Translation\GetText\GetTextEntry
     */
    class TranslationImportPdoExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO | \PHPUnit_Framework_MockObject_MockObject */
        private $pdo;

        /** @var PoToMySqlImporter */
        private $importer;

        /** @var GetTextEntry[] */
        private $entries;

        protected function setUp()
        {
            $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
            $this->importer = new PoToMySqlImporter($this->pdo);

            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId('testId');
            $getTextEntry->setMsgGerman('testGerman');
            $getTextEntry->setMsgFrench('testFrench');
            $this->entries = [
                $getTextEntry,
            ];
        }

        public function testPdoExceptionIsThrownIfImportFails()
        {
            $pdoException = new \PDOException();
            $this->expectException(GetTextImportException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($pdoException);

            $this->importer->import($this->entries);
        }
    }
}
