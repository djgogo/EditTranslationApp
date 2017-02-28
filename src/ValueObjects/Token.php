<?php

namespace Translation\ValueObjects
{
    class Token
    {
        /** @var null|string */
        private $tokenValue = '';

        public function __construct($value = null)
        {
            if ($value !== null) {
                $this->tokenValue = $value;
            } else {
                $this->setValue();
            }
        }

        private function setValue()
        {
            /** Algorithmus by Arne Blankerts - thephp.cc */
            $source = file_get_contents('/dev/urandom', false, null, null, 64);
            $source .= uniqid(uniqid(mt_rand(0, PHP_INT_MAX), true), true);
            for ($t = 0; $t < 64; $t++) {
                $source .= chr((mt_rand() ^ mt_rand()) % 256);
            }
            $this->tokenValue = sha1(hash('sha512', $source, true));
        }

        public function isEqualTo(string $token): bool
        {
            return $this->tokenValue === $token;
        }

        public function __toString(): string
        {
            return $this->tokenValue;
        }
    }
}
