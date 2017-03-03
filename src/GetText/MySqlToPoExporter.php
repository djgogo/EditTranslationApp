<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextExportException;

    class MySqlToPoExporter
    {
        /** @var \PDO */
        private $pdo;

        /** @var GetTextEntry[] */
        private $entries;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function export()
        {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM translations");
                $stmt->execute();

                $this->entries = $stmt->fetchAll(\PDO::FETCH_CLASS, GetTextEntry::class);

            } catch (\PDOException $e) {
                throw new GetTextExportException('Fehler beim lesen der i18n Translations Tabelle.', 0, $e);
            }
        }

        public function writeGermanPoGetTextFile(string $filename)
        {
            if (file_exists($filename)) {
                throw new GetTextExportException('Datei "' . $filename . '" existiert bereits');
            }

            foreach ($this->entries as $entry) {

                /**
                 * Schreiben Message Id
                 */
                $translationString = sprintf(
                    "msgid \"%s\"", $this->replaceBlankWithUnderline($entry->getMsgId())
                    ) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new GetTextExportException(
                        'In die Datei "' . $filename . '" konnte nicht geschrieben werden.'
                    );
                }

                /**
                 * Schreiben Message String
                 */
                $translationString = sprintf("msgstr \"%s\"\n", $entry->getMsgGerman()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new GetTextExportException(
                        'In die Datei "' . $filename . '" konnte nicht geschrieben werden.'
                    );
                }
            }
        }

        public function writeFrenchPoGetTextFile(string $filename)
        {
            if (file_exists($filename)) {
                throw new GetTextExportException('Datei "' . $filename . '" existiert bereits');
            }

            foreach ($this->entries as $entry) {

                /**
                 * Schreiben Message Id
                 */
                $translationString = sprintf(
                    "msgid \"%s\"", $this->replaceBlankWithUnderline($entry->getMsgId())
                    ) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new GetTextExportException(
                        'In die Datei "' . $filename . '" konnte nicht geschrieben werden.'
                    );
                }

                /**
                 * Schreiben Message String
                 */
                $translationString = sprintf("msgstr \"%s\"\n", $entry->getMsgFrench()) . PHP_EOL;
                $result = file_put_contents($filename, $translationString, FILE_APPEND);
                if ($result === false) {
                    throw new GetTextExportException(
                        'In die Datei "' . $filename . '" konnte nicht geschrieben werden.'
                    );
                }
            }
        }

        private function replaceBlankWithUnderline($str) : string
        {
            return str_replace(' ', '_', $str);
        }

        public function getProcessedEntries(): int
        {
            return count($this->entries);
        }
    }
}
