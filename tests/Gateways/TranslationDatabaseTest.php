<?php

namespace Translation\Gateways
{

    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Loggers\ErrorLogger;
    use Translation\ParameterObjects\TranslationParameterObject;

    /**
     * @covers Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Exceptions\TranslationTableGatewayException
     * @uses Translation\Loggers\ErrorLogger
     * @uses Translation\ParameterObjects\TranslationParameterObject
     */
    class TranslationDatabaseTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO | \PHPUnit_Framework_MockObject_MockObject */
        private $pdo;

        /** @var ErrorLogger | \PHPUnit_Framework_MockObject_MockObject */
        private $logger;

        /** @var TranslationTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $dataGateway;

        /** @var \PDOException */
        private $exception;

        /** @var TranslationParameterObject */
        private $parameterObject;

        protected function setUp()
        {
            $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
            $this->logger = $this->getMockBuilder(ErrorLogger::class)->disableOriginalConstructor()->getMock();
            $this->parameterObject = $this->getMockBuilder(TranslationParameterObject::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->dataGateway = new TranslationTableDataGateway($this->pdo, $this->logger);
            $this->exception = new \PDOException();
        }

        public function testPdoExceptionIsLoggedAndRethrownIfGetAllTranslationsFails()
        {
            $this->expectException(TranslationTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Fehler beim Lesen aller Datensätze der Translations Tabelle.', $this->exception);

            $this->dataGateway->getAllTranslations();
        }

        public function testPdoExceptionIsLoggedAndRethrownIfGetAllTranslationsOrderedByUpdatedFails()
        {
            $this->expectException(TranslationTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Fehler beim Lesen aller Datensätze der Translations Tabelle mit Sortierung.', $this->exception);

            $this->dataGateway->getAllTranslationsOrderedByUpdated('ASC');
        }

        public function testPdoExceptionIsLoggedAndRethrownInIfSearchTranslationFails()
        {
            $this->expectException(TranslationTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Fehler beim Lesen der Translations Tabelle mit Search-Parameter.', $this->exception);

            $this->dataGateway->getSearchedTranslation('searchString');
        }

        public function testPdoExceptionIsLoggedAndRethrownInFindTranslationById()
        {
            $this->expectException(TranslationTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with("Fehler beim Lesen der Translations Tabelle mit der Id: 'testId'", $this->exception);

            $this->dataGateway->findTranslationById('testId');
        }

        public function testPdoExceptionIsLoggedAndRethrownInUpdate()
        {
            $this->expectException(TranslationTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Fehler beim Ändern eines Datensatzes der Translation Tabelle.', $this->exception);

            $this->dataGateway->update($this->parameterObject);
        }
    }
}
