<?php

namespace Translation\ValueObjects
{
    class Username
    {
        /** @var string */
        private $username;

        public function __construct(string $username)
        {
            $this->ensureUsernameIsValid($username);
            $this->username = $username;
        }

        private function ensureUsernameIsValid(string $username)
        {
            if (strlen($username) > 50) {
                throw new \InvalidArgumentException('Benutzername: "' . $username . '" darf maximal 50 Zeichen lang sein.');
            }
        }

        public function __toString(): string
        {
            return (string) $this->username;
        }
    }
}
