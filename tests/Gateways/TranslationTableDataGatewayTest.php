<?php

namespace Translation\Gateways
{

    use Translation\Entities\Translation;
    use Translation\Loggers\ErrorLogger;
    use Translation\ParameterObjects\TranslationParameterObject;

    /**
     * @covers Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Loggers\ErrorLogger
     * @uses Translation\Entities\Translation
     * @uses Translation\ParameterObjects\TranslationParameterObject
     */
    class TranslationTableDataGatewayTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO */
        private $pdo;

        /** @var ErrorLogger | \PHPUnit_Framework_MockObject_MockObject */
        private $logger;

        /** @var TranslationTableDataGateway */
        private $gateway;

        protected function setUp()
        {
            $this->logger = $this->getMockBuilder(ErrorLogger::class)->disableOriginalConstructor()->getMock();
            $this->pdo = $this->initDatabase();
            $this->gateway = new TranslationTableDataGateway($this->pdo, $this->logger);
        }

        public function testAllTranslationsCanBeRetrievedFromDatabase()
        {
            $translations = $this->gateway->getAllTranslations();
            $this->assertInstanceOf(Translation::class, $translations[0]);
            $this->assertEquals('GermanText', $translations[0]->getMsgGerman());
        }

        public function testSearchedTranslationCanBeFound()
        {
            $translations = $this->gateway->getSearchedTranslation('GermanText');
            $this->assertEquals('GermanText', $translations[0]->getMsgGerman());
        }

        public function testTranslationCanBeFoundById()
        {
            $translation = $this->gateway->findTranslationById('testId');
            $this->assertEquals('testId', $translation->getMsgId());
        }

        public function testTranslationsCanBeSortedAscendingByUpdated()
        {
            $translations = $this->gateway->getAllTranslationsOrderedByUpdated('ASC');
            $this->assertEquals('testId', $translations[0]->getMsgId());
        }

        public function testTranslationsCanBeSortedDescendingByUpdated()
        {
            $translations = $this->gateway->getAllTranslationsOrderedByUpdated('DESC');
            $this->assertEquals('testId2', $translations[0]->getMsgId());
        }

        public function testTranslationCanBeUpdated()
        {
            $requestFormValues = new TranslationParameterObject(
                'testId',
                'changed German Text',
                'changed French Text',
                date("Y-m-d H:i:s")
            );
            $this->gateway->update($requestFormValues);

            $translation = $this->gateway->findTranslationById('testId');
            $this->assertEquals('changed German Text', $translation->getMsgGerman());
            $this->assertEquals('changed French Text', $translation->getMsgFrench());
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

            // Insert first row
            $msgId = 'testId';
            $msgGerman = 'GermanText';
            $msgFrench = 'FrenchText';
            $created = '2017-02-23 12:00:00';
            $updated = '2017-02-24 12:00:00';

            $stmt = $pdo->prepare(
                'INSERT INTO translations (msgId, msgGerman, msgFrench, created, updated)
                VALUES (:msgId, :msgGerman, :msgFrench, :created, :updated)'
            );

            $stmt->bindParam(':msgId', $msgId, \PDO::PARAM_STR);
            $stmt->bindParam(':msgGerman', $msgGerman, \PDO::PARAM_STR);
            $stmt->bindParam(':msgFrench', $msgFrench, \PDO::PARAM_STR);
            $stmt->bindParam(':created', $created, \PDO::PARAM_STR);
            $stmt->bindParam(':updated', $updated, \PDO::PARAM_STR);
            $stmt->execute();

            // Insert second row
            $msgId2 = 'testId2';
            $msgGerman2 = 'GermanText2';
            $msgFrench2 = 'FrenchText2';
            $created2 = '2017-02-23 12:00:00';
            $updated2 = '2017-02-24 18:00:00';

            $stmt = $pdo->prepare(
                'INSERT INTO translations (msgId, msgGerman, msgFrench, created, updated)
                VALUES (:msgId, :msgGerman, :msgFrench, :created, :updated)'
            );

            $stmt->bindParam(':msgId', $msgId2, \PDO::PARAM_STR);
            $stmt->bindParam(':msgGerman', $msgGerman2, \PDO::PARAM_STR);
            $stmt->bindParam(':msgFrench', $msgFrench2, \PDO::PARAM_STR);
            $stmt->bindParam(':created', $created2, \PDO::PARAM_STR);
            $stmt->bindParam(':updated', $updated2, \PDO::PARAM_STR);
            $stmt->execute();

            $query = $pdo->query('SELECT * FROM translations');
            $result = $query->fetchAll(\PDO::FETCH_COLUMN);
            if (count($result) != 2) {
                throw new \Exception('Datenbank konnte nicht initialisiert werden!');
            }

            return $pdo;
        }
    }
}
