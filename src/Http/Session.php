<?php

namespace Translation\Http {

    use Translation\Exceptions\SessionException;

    class Session
    {
        /** @var array */
        public $data;

        public function __construct(array $session)
        {
            $this->data = $session;
        }

        /** @param mixed $value */
        public function setValue(string $key, $value)
        {
            $this->data[$key] = $value;
        }

        public function isset(string $key): bool
        {
            return isset($this->data[$key]);
        }

        /**
         * Es wird keine Execption geworfen falls $key nicht gefunden wird. Die Templating Engine Twig braucht
         * ein leerer string oder null wenn ein Value nicht gesetzt ist.
         * @return mixed|string
         */
        public function getValue(string $key, $default = '')
        {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
            return $default;
        }

        public function deleteValue(string $key)
        {
            if (!isset($this->data[$key])) {
                throw new SessionException("Schlüssel '" . $key . "' existiert nicht.");
            }
            unset($this->data[$key]);
        }

        public function getSessionData(): array
        {
            if ($this->data !== null) {
                return $this->data;
            }
            return array();
        }

        public function isLoggedIn(): bool
        {
            return isset($this->data['user']);
        }
    }
}
