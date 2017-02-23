<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextExportException;

    /**
     * @covers Translation\GetText\MySqlToPoExporter
     * @uses Translation\GetText\GetTextEntry
     */
    class TranslationExportPdoExceptionTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO | \PHPUnit_Framework_MockObject_MockObject */
        private $pdo;

        /** @var MySqlToPoExporter */
        private $exporter;

        /** @var GetTextEntry[] */
        private $entries;

        protected function setUp()
        {
            $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
            $this->exporter = new MySqlToPoExporter($this->pdo);

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
            $this->expectException(GetTextExportException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($pdoException);

            $this->exporter->export();
        }
    }
}
