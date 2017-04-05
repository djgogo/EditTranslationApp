<?php

namespace Translation\Forms
{
    use Translation\Http\Session;

    class FormPopulate implements FormInterface
    {
        /** @var array */
        private $data = [];

        /** @var Session */
        private $session;

        public function __construct(Session $session)
        {
            $this->session = $session;
        }

        public function set(string $key, string $value)
        {
            $this->data[$key] = $value;
            $this->session->setValue('populate', $this);
        }

        public function has(string $key): bool
        {
            return array_key_exists($key, $this->data);
        }

        public function remove(string $key)
        {
            if ($this->has($key)) {
                unset($this->data[$key]);
            }
        }

        public function get(string $key, string $default = ''): string
        {
            if ($this->has($key)) {
                $value = $this->data[$key];
                unset($this->data[$key]);
                return $value;
            }
            return $default;
        }
    }
}
