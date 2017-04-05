<?php

namespace Translation\GetText
{

    use Translation\Exceptions\GetTextImportException;

    class PoToMySqlImporter
    {
        /** @var \PDO */
        private $pdo;

        /** @var int */
        private $processedRecords;

        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function import(array $getTextEntry)
        {
            /**
             * @var $entry GetTextEntry
             */
            foreach ($getTextEntry as $entry) {

                $msgId = $entry->getMsgId();
                $msgGerman = $entry->getMsgGerman();
                $msgFrench = $entry->getMsgFrench();
                $created = date("Y-m-d H:i:s");

                try {
                    $stmt = $this->pdo->prepare(
                        'INSERT INTO translations (msgId, msgGerman, msgFrench, created) 
            VALUES (:msgId,
                    :msgGerman, 
                    :msgFrench, 
                    :created)'
                    );

                    $stmt->bindParam(':msgId', $msgId, \PDO::PARAM_STR);
                    $stmt->bindParam(':msgGerman', $msgGerman, \PDO::PARAM_STR);
                    $stmt->bindParam(':msgFrench', $msgFrench, \PDO::PARAM_STR);
                    $stmt->bindParam(':created', $created, \PDO::PARAM_STR);

                    $stmt->execute();
                    $this->processedRecords++;

                } catch (\PDOException $e) {
                    throw new GetTextImportException('Translations konnten nicht importiert werden.', 0, $e);
                }
            }
        }

        public function getProcessedEntries() :int
        {
            return $this->processedRecords;
        }
    }
}
