<?php

namespace Translation\Factories {

    use Translation\Exceptions\InvalidPdoAttributeException;

    class PDOFactory
    {
        /** @var string */
        private $host;

        /** @var string */
        private $dbName;

        /** @var string */
        private $user;

        /** @var string */
        private $pass;

        /** @var string */
        private $charset;

        /** @var \PDO */
        private $instance = null;

        public function __construct(string $host, string $dbName, string $user, string $pass, string $charset)
        {
            $this->host = $host;
            $this->dbName = $dbName;
            $this->user = $user;
            $this->pass = $pass;
            $this->charset = $charset;
        }

        public function getDbHandler() : \PDO
        {
            if ($this->instance === null) {
                $this->instance = $this->getPdo($this->host, $this->dbName, $this->user, $this->pass, $this->charset);
            }
            return $this->instance;
        }

        private function getPdo($host, $dbName, $user, $pass, $charset) : \PDO
        {
            try {
                $db = new \PDO(
                    "mysql:host=$host;dbname=$dbName;charset=$charset",
                    $user,
                    $pass
                );

                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $db;

            } catch (\PDOException $e) {
                throw new InvalidPdoAttributeException('Wrong mySql Credentials or mySql Database down', 0, $e);
            }
        }
    }
}
