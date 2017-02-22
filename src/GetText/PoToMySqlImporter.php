<?php

namespace Translation\GetText
{

    use Translation\Factories\PDOFactory;

    class PoToMySqlImporter
    {
        /** @var \PDO */
        private $pdo;

        /** @var int */
        private $processedRecords;

        public function __construct(PDOFactory $factory)
        {
            $this->pdo = $factory->getDbHandler();
        }

        public function import(array $getTextEntry)
        {
            /**
             * @var $entry GetTextEntry
             */
            foreach ($getTextEntry as $entry) {

                $msgId = $entry->getMsgId();
                $msgGerman = $entry->getMsgStrGerman();
                $msgFrench = $entry->getMsgStrFrench();
                $created = date("Y-m-d H:i:s");

                try {
                    $stmt = $this->pdo->prepare(
                        'INSERT INTO i18n.translations (msgId, msgGerman, msgFrench, created) 
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
                    throw new \Exception('Translations konnten nicht importiert werden.', 0, $e);
                }
            }
        }

        public function getProcessedEntries() :int
        {
            return $this->processedRecords;
        }
    }
}
