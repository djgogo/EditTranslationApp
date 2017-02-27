<?php

namespace Translation\ValueObjects
{
    class Password
    {
        /** @var string */
        private $password;

        public function __construct(string $password)
        {
            $this->ensurePasswordIsBigEnough($password);
            $this->ensurePasswordIsNotToBig($password);
            $this->password = $password;
        }

        private function ensurePasswordIsBigEnough(string $password)
        {
            if (strlen($password) < 6) {
                throw new \InvalidArgumentException('Passwort: "' . $password . '" sollte mindestens 6 Zeichen lang sein.');
            }
        }

        private function ensurePasswordIsNotToBig(string $password)
        {
            if (strlen($password) > 255) {
                throw new \InvalidArgumentException('Passwort: "' . $password . '" ist zu lang.');
            }
        }

        public function __toString(): string
        {
            return (string) $this->password;
        }
    }
}
