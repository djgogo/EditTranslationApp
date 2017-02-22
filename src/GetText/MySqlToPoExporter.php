<?php

namespace Translation\GetText
{

    use Translation\Factories\PDOFactory;

    class MySqlToPoExporter
    {
        /** @var \PDO */
        private $pdo;

        /** @var GetTextEntry[] */
        private $entries;

        public function __construct(PDOFactory $factory)
        {
            $this->pdo = $factory->getDbHandler();
        }

        public function export()
        {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM i18n.translations");
                $stmt->execute();

                $this->entries = $stmt->fetchAll(\PDO::FETCH_CLASS, GetTextEntry::class);

            } catch (\PDOException $e) {
                throw new \Exception('Fehler beim lesen der i18n Translations Tabelle.', 0, $e);
            }
        }

        public function writeGermanPoGetTextFile(string $filename)
        {
            if (file_exists($filename)) {
                throw new \Exception('Datei "' . $filename . '" existiert bereits');
            }

            foreach ($this->entries as $entry) {

                /**
                 * Schreiben Message Id
                 */
                $translationString = sprintf("msgid \"%s\"", $entry->getMsgId()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new \Exception('In die Datei "' . $filename . '" konte nicht geschrieben werden.');
                }

                /**
                 * Schreiben Message String
                 */
                $translationString = sprintf("msgstr \"%s\"\n", $entry->getMsgGerman()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new \Exception('In die Datei "' . $filename . '" konte nicht geschrieben werden.');
                }
            }
        }

        public function writeFrenchPoGetTextFile(string $filename)
        {
            if (file_exists($filename)) {
                throw new \Exception('Datei "' . $filename . '" existiert bereits');
            }

            foreach ($this->entries as $entry) {

                /**
                 * Schreiben Message Id
                 */
                $translationString = sprintf("msgid \"%s\"", $entry->getMsgId()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new \Exception('In die Datei "' . $filename . '" konte nicht geschrieben werden.');
                }

                /**
                 * Schreiben Message String
                 */
                $translationString = sprintf("msgstr \"%s\"\n", $entry->getMsgFrench()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new \Exception('In die Datei "' . $filename . '" konte nicht geschrieben werden.');
                }
            }
        }

        public function getProcessedEntries() : int
        {
            return count($this->entries);
        }
    }
}
