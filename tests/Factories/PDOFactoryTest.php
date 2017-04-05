<?php

namespace Translation\Factories
{

    use Translation\Exceptions\InvalidPdoAttributeException;

    /**
     * @covers Translation\Factories\PDOFactory
     */
    class PDOFactoryTest extends \PHPUnit_Framework_TestCase
    {
        /** @var PDOFactory */
        private $pdoFactory;

        protected function setUp()
        {
            $this->pdoFactory = new PDOFactory('localhost', 'i18n', 'AdminUser', 'A_User++', 'utf8');
        }

        public function testPdoDatabaseHandlerCanBeRetrieved()
        {
            $this->assertInstanceOf(\PDO::class, $this->pdoFactory->getDbHandler());
        }

        public function testPdoIsAlwaysTheSameObject()
        {
            $this->assertSame($this->pdoFactory->getDbHandler(), $this->pdoFactory->getDbHandler());
            $this->assertInstanceOf(\PDO::class, $this->pdoFactory->getDbHandler());
        }

        public function testDbHandlerWithWrongCredentialsThrowsException()
        {
            $this->expectException(InvalidPdoAttributeException::class);
            $wrongPdo = new PDOFactory('localhost', 'i18n', 'anyUser', 'anyPassword', 'utf8');
            $wrongPdo->getDbHandler();
        }
    }
}
