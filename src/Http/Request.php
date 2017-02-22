<?php

namespace Translation\Http {

    use Translation\Exceptions\RequestValueNotFoundException;

    class Request
    {
        /** @var array */
        private $request;

        /** @var array */
        private $server;

        public function __construct(array $request, array $server)
        {
            $this->request = $request;
            $this->server = $server;
        }

        public function getRequestUri(): string
        {
            return $this->server['REQUEST_URI'];
        }

        public function getRequestMethod(): string
        {
            return $this->server['REQUEST_METHOD'];
        }

        public function isPostRequest(): bool
        {
            return ($this->getRequestMethod() === 'POST');
        }

        public function isGetRequest(): bool
        {
            return ($this->getRequestMethod() === 'GET');
        }

        public function isLoggedIn(): bool
        {
            return isset($this->server['PHP_AUTH_USER']);
        }

        public function hasValue($key): bool
        {
            return isset($this->request[$key]);
        }

        public function getValue($key): string
        {
            if (!$this->hasValue($key)) {
                throw new RequestValueNotFoundException("Value '" . $key . "' nicht gefunden.");
            }
            return $this->request[$key];
        }
    }
}
