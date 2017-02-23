<?php

namespace Translation\GetText
{
    use Translation\Exceptions\GetTextFileException;

    /**
     * POParser parst nur folgende States: msgid, msgstr
     * und erstellt ein neues Array von GetTextEntry Objekten die folgende Attribute enthalten:
     * msgid mit $key und msgstr mit $value
     */
    class PoParserGerman
    {
        /** @var GetTextEntry[] */
        private $entries;

       /** @var string */
        private $filePath;

        /** @var string */
        private $msgId;

        public function __construct(string $filePath)
        {
            $this->filePath = $filePath;
        }

        public function parse(): array
        {
            $handle = $this->load($this->filePath);

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

            if (!file_exists($filePath)) {
                throw new GetTextFileException('Datei "' . $filePath . '" existiert nicht');
            }

            if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'po') {
                throw new GetTextFileException('Die angegebene Datei "' . $filePath . '" ist keine .po Datei');
            }

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
                $this->addToEntries($this->msgId, $value);
            } else {
                $this->msgId = $value;
            }
        }

        private function addToEntries($msgId, $value)
        {
            $getTextEntry = new GetTextEntry();
            $getTextEntry->setMsgId($msgId);
            $getTextEntry->setMsgGerman($value);

            $this->entries[] = $getTextEntry;
        }

        private function deQuote($str): string
        {
            return substr($str, 1, -1);
        }

        public function getProcessedTranslations(): int
        {
            return count($this->entries);
        }
    }
}
