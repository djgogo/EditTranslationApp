<?php

namespace Translation\Routers
{
    use Translation\Factories\Factory;
    use Translation\Http\Request;
    use Translation\Http\Session;
    use Translation\Loggers\ErrorLogger;

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

            switch ($path) {
                case '/updateTranslation':
                    return $this->factory->getUpdateTranslationController();
                case '/login':
                    return $this->factory->getLoginController();
                default:
                    return null;
            }
        }
    }
}
