<?php

namespace Translation\ParameterObjects
{
    class UserParameterObject
    {
        /** @var string */
        private $username;

        /** @var string */
        private $password;

        /** @var string */
        private $email;

        public function __construct($username, $password, $email)
        {
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
        }

        public function getUsername(): string
        {
            return $this->username;
        }
        public function getPassword(): string
        {
            return $this->password;
        }

        public function getEmail(): string
        {
            return $this->email;
        }
    }
}
