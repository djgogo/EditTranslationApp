<?php

namespace Translation\Loggers
{
    /**
     * @covers Translation\Loggers\ErrorLogger
     */
    class ErrorLoggerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var \DateTime */
        private $dateTime;

        /** @var string */
        private $path;

        /** @var \Exception */
        private $exception;

        /** @var ErrorLogger */
        private $errorLogger;

        public function setUp()
        {
            $this->dateTime = new \DateTime();
            $this->path = __DIR__ . '/../Loggers/errorTest.log';
            $this->exception = new \Exception();

            $this->errorLogger = new ErrorLogger($this->dateTime, $this->path);
        }

        public function testLoggingAnExceptionWorks()
        {
            $expectedString = 'Test Exception Logging / /0 / /var/www/EditTranslationApp/tests/Loggers/ErrorLoggerTest.php / 26';
            $this->errorLogger->log('Test Exception Logging', $this->exception);

            $logStringWithoutDateTime = trim(substr(file_get_contents($this->path), 22));
            $this->assertEquals($expectedString, $logStringWithoutDateTime);

            unlink($this->path);
        }

        public function testLoggingAMessageWorks()
        {
            $backtrace = [
                ['file' => '/testFile', 'line' => '99']
            ];

            $expectedString = 'Test Message Logging / /testFile / 99';
            $this->errorLogger->logMessage('Test Message Logging', $backtrace);

            $logStringWithoutDateTime = trim(substr(file_get_contents($this->path), 22));
            $this->assertEquals($expectedString, $logStringWithoutDateTime);

            unlink($this->path);
        }
    }
}
