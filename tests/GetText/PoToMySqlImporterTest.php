<?php

namespace Translation\GetText
{
    /**
     * @covers Translation\GetText\PoToMySqlImporter
     * @uses Translation\GetText\GetTextEntry
     */
    class PoToMySqlImporterTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO */
        private $pdo;

        /** @var PoToMySqlImporter */
        private $importer;

        /** @var GetTextEntry[] */
        private $entries;

        protected function setUp()
        {
            $this->pdo = $this->initDatabase();
            $this->importer = new PoToMySqlImporter($this->pdo);

            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId('testId');
            $getTextEntry->setMsgGerman('testGerman');
            $getTextEntry->setMsgFrench('testFrench');
            $this->entries = [
                $getTextEntry,
            ];
        }

        public function testGetTextEntryCanBeImportedToDatabase()
        {
            $this->importer->import($this->entries);
            $this->assertSame('testId', $this->export()->getMsgId());
            $this->assertEquals(1, $this->importer->getProcessedEntries());
        }

        private function export(): GetTextEntry
        {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM translations");
                $stmt->execute();

                return $stmt->fetchObject(GetTextEntry::class);

            } catch (\PDOException $e) {
                throw new \Exception('Fehler beim lesen der i18n Translations Tabelle.', 0, $e);
            }
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
