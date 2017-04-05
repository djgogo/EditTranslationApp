<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextFileException;

    /**
     * AbstractPoParser parst nur folgende States: msgid, msgstr
     * und erstellt ein neues Array von GetTextEntry Objekten die folgende Attribute enthalten:
     * msgid mit $key und msgstr mit $value
     */
    abstract class AbstractPoParser
    {
        /** @var GetTextEntry[] */
        private $entries;

        /** @var string */
        private $msgId;

        public function __construct(array $getTextEntries)
        {
            $this->entries = $getTextEntries;
        }

        public function parse(string $filePath): array
        {
            $handle = $this->load($filePath);

            if ($handle) {
                while (!feof($handle)) {
                    $line = fgets($handle);

                    if ($line != '' && $line[0] === 'm') {
                        $this->processLine(trim($line));
                    }
                }
                fclose($handle);
            }

            return $this->entries;
        }

        private function load($filePath)
        {
            if (empty($filePath)) {
                throw new GetTextFileException('Input Datei nicht definiert.');
            }

            if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'po') {
                throw new GetTextFileException('Die angegebene Datei "' . $filePath . '" ist keine .po Datei');
            }

            if (!file_exists($filePath)) {
                throw new GetTextFileException('Datei "' . $filePath . '" existiert nicht');
            }

            $handle = $this->openFile($filePath);
            return $handle;
        }

        public function openFile(string $filePath)
        {
            $handle = fopen($filePath, 'r');
            if ($handle === false) {
                throw new GetTextFileException('Datei "' . $filePath . '" konnte nicht geöffnet werden');
            }
            return $handle;
        }

        private function processLine($line)
        {
            $split = explode(' ', $line, 2);
            $state = $split[0];
            $value = $split[1];

            if ($state != 'msgid' && $state != 'msgstr') {
                return;
            }

            $value = $this->deQuote($value);

            if ($value === '') {
                return;
            }

            if ($state === 'msgstr') {
                $this->addToEntries($this->replaceUnderlines($this->msgId), $value);
            } else {
                $this->msgId = $value;
            }
        }

        abstract protected function addToEntries(string $msgId, string $value);

        private function deQuote($str): string
        {
            return substr($str, 1, -1);
        }

        private function replaceUnderlines($str): string
        {
            return str_replace('_', ' ', $str);
        }

        public function getProcessedTranslations(): int
        {
            return count($this->entries);
        }

        protected function addGetTextEntry(GetTextEntry $entry)
        {
            $this->entries[] = $entry;
        }

        protected function getEntries(): array
        {
            return $this->entries;
        }
    }
}
