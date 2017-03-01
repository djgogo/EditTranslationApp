<?php

namespace Translation\Gateways
{

    use Translation\Exceptions\UserTableGatewayException;
    use Translation\Loggers\ErrorLogger;
    use Translation\ParameterObjects\UserParameterObject;

    /**
     * @covers Translation\Gateways\UserTableDataGateway
     * @uses Translation\Exceptions\UserTableGatewayException
     * @uses Translation\Loggers\ErrorLogger
     * @uses Translation\ParameterObjects\UserParameterObject
     */
    class UserDatabaseTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \PDO | \PHPUnit_Framework_MockObject_MockObject */
        private $pdo;

        /** @var ErrorLogger | \PHPUnit_Framework_MockObject_MockObject */
        private $logger;

        /** @var UserParameterObject | \PHPUnit_Framework_MockObject_MockObject */
        private $parameterObject;

        /** @var UserTableDataGateway */
        private $dataGateway;

        /** @var \PDOException */
        private $exception;

        protected function setUp()
        {
            $this->pdo = $this->getMockBuilder(\PDO::class)->disableOriginalConstructor()->getMock();
            $this->logger = $this->getMockBuilder(ErrorLogger::class)->disableOriginalConstructor()->getMock();
            $this->parameterObject = $this->getMockBuilder(UserParameterObject::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->dataGateway = new UserTableDataGateway($this->pdo, $this->logger);
            $this->exception = new \PDOException();
        }

        public function testPdoExceptionIsLoggedAndRethrownIfInsertFails()
        {
            $this->expectException(UserTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Benutzer "" konnte nicht eingefügt werden.', $this->exception);

            $this->dataGateway->insert($this->parameterObject);
        }

        public function testPdoExceptionIsLoggedAndRethrownIfFindUserByCredentialsFails()
        {
            $this->expectException(UserTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Benutzer "testUser" konnte nicht gefunden werden.', $this->exception);

            $this->dataGateway->findUserByCredentials('testUser', '123456');
        }

        public function testPdoExceptionIsLoggedAndRethrownIfFindUserByUsernameFails()
        {
            $this->expectException(UserTableGatewayException::class);

            $this->pdo
                ->expects($this->once())
                ->method('prepare')
                ->willThrowException($this->exception);

            $this->logger
                ->expects($this->once())
                ->method('log')
                ->with('Benutzer "testUser" konnte nicht gefunden werden.', $this->exception);

            $this->dataGateway->findUserByUsername('testUser');
        }
    }
}
