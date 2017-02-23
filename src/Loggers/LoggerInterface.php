<?php

namespace Translation\Loggers {

    interface LoggerInterface
    {
        /**
         * loggt eine Message und die Exception Details
         */
        public function log(string $message, \Exception $e = null);

        /**
         * loggt eine Message, den Filenamen (inkl. Pfad) und Code-Zeile
         */
        public function logMessage(string $message, $backtrace = []);
    }
}
