<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextExportException;

    /**
     * @covers Translation\GetText\MySqlToPoExporter
     * @uses Translation\GetText\GetTextEntry
     */
    class MySqlToPoExporterTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO */
        private $pdo;

        /** @var MySqlToPoExporter */
        private $exporter;

        /** @var GetTextEntry[] */
        private $entries;

        /** @var string */
        private $file;

        protected function setUp()
        {
            $this->file = __DIR__ . '/TestFiles/testFile.po';

            $this->pdo = $this->initDatabase();
            $this->exporter = new MySqlToPoExporter($this->pdo);

            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId('FooId');
            $getTextEntry->setMsgGerman('testGerman');
            $getTextEntry->setMsgFrench('testFrench');
            $this->entries = [
                $getTextEntry,
            ];
        }

        public function testTranslationsCanBeExported()
        {
            $this->exporter->export();
            $this->assertSame('FooId', $this->entries[0]->getMsgId());
            $this->assertEquals(1, $this->exporter->getProcessedEntries());
        }

        public function testGermanPoGetTextFileCanBeWritten()
        {
            unlink($this->file);
            $this->exporter->export();
            $this->exporter->writeGermanPoGetTextFile($this->file);

            $expected = 'msgid "testId"';
            $line = fgets(fopen($this->file, 'r'));
            $this->assertEquals($expected, trim($line));
            unlink($this->file);
        }

        public function testExporterThrowsExceptionIfGermanPoFileAlreadyExists()
        {
            $this->expectException(GetTextExportException::class);

            touch($this->file);
            $this->exporter->export();
            $this->exporter->writeGermanPoGetTextFile($this->file);
            unlink($this->file);
        }

        public function testFrenchPoGetTextFileCanBeWritten()
        {
            unlink($this->file);
            $this->exporter->export();
            $this->exporter->writeFrenchPoGetTextFile($this->file);

            $expected = 'msgid "testId"';
            $line = fgets(fopen($this->file, 'r'));
            $this->assertEquals($expected, trim($line));
            unlink($this->file);
        }

        public function testExporterThrowsExceptionIfFrenchPoFileAlreadyExists()
        {
            $this->expectException(GetTextExportException::class);

            touch($this->file);
            $this->exporter->export();
            $this->exporter->writeFrenchPoGetTextFile($this->file);
            unlink($this->file);
        }

        private function initDatabase()
        {
            $pdo = new \PDO('sqlite::memory:');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $pdo->query(
                'CREATE TABLE translations (
                msgId VARCHAR(100) NOT NULL ,
                msgGerman VARCHAR(1024) NOT NULL,
                msgFrench VARCHAR(1024) NOT NULL,
                created DATETIME NOT NULL,
                updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)'
            );

            // Insert row
            $msgId = 'testId';
            $msgGerman = 'GermanText';
            $msgFrench = 'FrenchText';
            $created = date("Y-m-d H:i:s");

            $stmt = $pdo->prepare(
                'INSERT INTO translations (msgId, msgGerman, msgFrench, created)
                VALUES (:msgId, :msgGerman, :msgFrench, :created)'
            );

            $stmt->bindParam(':msgId', $msgId, \PDO::PARAM_STR);
            $stmt->bindParam(':msgGerman', $msgGerman, \PDO::PARAM_STR);
            $stmt->bindParam(':msgFrench', $msgFrench, \PDO::PARAM_STR);
            $stmt->bindParam(':created', $created, \PDO::PARAM_STR);
            $stmt->execute();

            $query = $pdo->query('SELECT * FROM translations');
            $result = $query->fetchAll(\PDO::FETCH_COLUMN);
            if (count($result) != 1) {
                throw new \Exception('Datenbank konnte nicht initialisiert werden!');
            }

            return $pdo;
        }
    }
}
