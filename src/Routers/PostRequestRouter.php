<?php

namespace Translation\Routers
{
    use Translation\Factories\Factory;
    use Translation\Http\Request;
    use Translation\Http\Session;
    use Translation\Loggers\ErrorLogger;
    use Translation\ValueObjects\Token;

    class PostRequestRouter implements RouterInterface
    {
        /** @var Factory */
        private $factory;

        /** @var Session */
        private $session;

        /** @var ErrorLogger */
        private $logger;

        public function __construct(Factory $factory, Session $session, ErrorLogger $logger)
        {
            $this->factory = $factory;
            $this->session = $session;
            $this->logger = $logger;
        }

        public function route(Request $request)
        {
            if (!$request->isPostRequest()) {
                return null;
            }

            $uri = $request->getRequestUri();
            $path = parse_url($uri, PHP_URL_PATH);

            if ($this->hasCsrfError($request->getValue('csrf'))) {
                return $this->factory->getError500Controller();
            }

            switch ($path) {
                case '/updateTranslation':
                    return $this->factory->getUpdateTranslationController();
                case '/login':
                    return $this->factory->getLoginController();
                default:
                    return null;
            }
        }

        private function hasCsrfError(string $token): bool
        {
            $sessionToken = $this->session->getValue('token');
            if ($sessionToken->isEqualTo($token)) {
                return false;
            }
            $message = 'Das übergebene Formular hat kein gültiges CSRF-Token!';
            $this->logger->logMessage($message, debug_backtrace());
            return true;
        }
    }
}
