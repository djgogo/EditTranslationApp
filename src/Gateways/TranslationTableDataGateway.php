<?php

namespace Translation\Gateways {

    use Translation\Entities\Translation;
    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Loggers\ErrorLogger;
    use Translation\ParameterObjects\TranslationParameterObject;

    class TranslationTableDataGateway
    {
        /** @var \PDO */
        private $pdo;

        /** @var ErrorLogger */
        private $logger;

        public function __construct(\PDO $pdo, ErrorLogger $logger)
        {
            $this->pdo = $pdo;
            $this->logger = $logger;
        }

        public function getAllTranslations(): array
        {
            try {
                $stmt = $this->pdo->prepare(
                    'SELECT msgId, msgGerman, msgFrench, created, updated 
                     FROM translations'
                );

                /**
                 * Stellt man PDO::ATTR_ERRMODE auf PDO::ERRMODE_EXCEPTION wirkt sich das auf beide PDO
                 * und PDO::PDOStatement Objekte aus. Auch bei: PDO::beginTransaction(), PDO::prepare(),
                 * PDOStatement::execute(), PDO::commit(), PDOStatement::fetch(), PDOStatement::fetchAll()
                 * und so weiter... wird eine Exception geworfen. Einige von diesen Funktionen sind in der
                 * Dokumentation so spezifiziert dass Sie bei einem Fehler 'false' zurückgeben. Dies ist
                 * hier nicht der Fall. Das heisst dass die folgende execute Methode nicht 'false'
                 * zurückgeben wird falls sie fehlschlägt, sondern wird eine PDOException werfen
                 * stattdessen, welche bereits abgefangen wird.
                 *
                 * Beschreibung von php.net
                 */
                $stmt->execute();
                return $stmt->fetchAll(\PDO::FETCH_CLASS, Translation::class);

            } catch (\PDOException $e) {
                $message = 'Fehler beim Lesen aller Datensätze der Translations Tabelle.';
                $this->logger->log($message, $e);
                throw new TranslationTableGatewayException($message);
            }
        }

        public function getAllTranslationsOrderedByUpdated(string $sort): array
        {
            try {
                $stmt = $this->pdo->prepare(
                    "SELECT msgId, msgGerman, msgFrench, created, updated 
                     FROM translations
                     ORDER BY updated $sort"
                );
                $stmt->execute();
                return $stmt->fetchAll(\PDO::FETCH_CLASS, Translation::class);

            } catch (\PDOException $e) {
                $message = 'Fehler beim Lesen aller Datensätze der Translations Tabelle mit Sortierung.';
                $this->logger->log($message, $e);
                throw new TranslationTableGatewayException($message);
            }
        }

        public function getSearchedTranslation(string $searchString): array
        {
            try {
                $stmt = $this->pdo->prepare(
                    'SELECT msgId, msgGerman, msgFrench, created, updated 
                     FROM translations 
                     WHERE msgId LIKE :search OR msgGerman LIKE :search OR msgFrench LIKE :search'
                );

                $search = '%' . $searchString . '%';
                $stmt->bindParam(':search', $search, \PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(\PDO::FETCH_CLASS, Translation::class);

            } catch (\PDOException $e) {
                $message = 'Fehler beim Lesen der Translations Tabelle mit Search-Parameter.';
                $this->logger->log($message, $e);
                throw new TranslationTableGatewayException($message);
            }
        }

        public function findTranslationById(string $msgId): Translation
        {
            try {
                $stmt = $this->pdo->prepare(
                    'SELECT msgId, msgGerman, msgFrench, created, updated 
                     FROM translations 
                     WHERE msgId=:msgId 
                     LIMIT 1'
                );
                $stmt->bindParam(':msgId', $msgId, \PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->fetchObject(Translation::class);
                return $result;

            } catch (\PDOException $e) {
                $message = "Fehler beim Lesen der Translations Tabelle mit der Id: '" . $msgId . "'";
                $this->logger->log($message, $e);
                throw new TranslationTableGatewayException($message);
            }
        }

        public function update(TranslationParameterObject $translation)
        {
            try {
                $stmt = $this->pdo->prepare(
                    'UPDATE translations 
                     SET msgId=:msgId, msgGerman=:msgGerman, msgFrench=:msgFrench, updated=:updated 
                     WHERE msgId=:msgId'
                );

                $stmt->bindValue(':msgId', $translation->getMsgId(), \PDO::PARAM_STR);
                $stmt->bindValue(':msgGerman', trim($translation->getMsgGerman()), \PDO::PARAM_STR);
                $stmt->bindValue(':msgFrench', trim($translation->getMsgFrench()), \PDO::PARAM_STR);
                $stmt->bindValue(':updated', $translation->getUpdated(), \PDO::PARAM_STR);

                $stmt->execute();

            } catch (\PDOException $e) {
                $message = 'Fehler beim Ändern eines Datensatzes der Translation Tabelle.';
                $this->logger->log($message, $e);
                throw new TranslationTableGatewayException($message);
            }
        }
    }
}
