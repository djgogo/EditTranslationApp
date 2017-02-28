<?php

namespace Translation\Gateways
{

    use Translation\Loggers\ErrorLogger;

    class UserTableDataGatewayTest extends \PHPUnit_Framework_TestCase
    {
        /** @var ErrorLogger | \PHPUnit_Framework_MockObject_MockObject */
        private $logger;

        /** @var \PDO */
        private $pdo;

        /** @var UserTableDataGateway */
        private $gateway;

        protected function setUp()
        {
            $this->pdo = $this->initDatabase();
            $this->logger = $this->getMockBuilder(ErrorLogger::class)->disableOriginalConstructor()->getMock();
            $this->gateway = new UserTableDataGateway($this->pdo, $this->logger);
        }

        private function initDatabase()
        {
            $pdo = new \PDO('sqlite::memory:');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $pdo->query(
                'CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(80) NOT NULL,
                created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)'
            );



        }
    }
}
