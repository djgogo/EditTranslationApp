<?php

namespace Translation\Loggers
{
    class ErrorLogger implements LoggerInterface
    {
        /** @var \DateTime */
        private $dateTime;

        /** @var string */
        private $path;

        public function __construct(\DateTime $dateTime, string $path)
        {
            $this->dateTime = $dateTime;
            $this->path = $path;
        }

        public function log(string $message, \Exception $e = null)
        {
            $logEntry = $this->dateTime->format('Y/m/d H:i:s') . ' / ' .
                $message . ' / ' .
                $e->getMessage() . '/' .
                $e->getCode() . ' / ' .
                $e->getFile() . ' / ' .
                $e->getLine();

            /**
             * message-type 3: message wird an die Datei (destination) angefügt.
             * Ein Zeilenumbruch wird nicht automatisch an das Ende des message-Strings angehängt.
             */
            error_log($logEntry . PHP_EOL, 3, $this->path);
        }

        public function logMessage(string $message, $b = [])
        {
            $logEntry = $this->dateTime->format('Y/m/d H:i:s') . ' / ' .
                $message . ' / ' .
                $b[0]['file'] . ' / ' .
                $b[0]['line'];

            error_log($logEntry . PHP_EOL, 3, $this->path);
        }
    }
}
