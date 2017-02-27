<?php

namespace Translation\Routers
{
    use Translation\Factories\Factory;
    use Translation\Http\Request;

    class GetRequestRouter implements RouterInterface
    {
        /** @var Factory */
        private $factory;

        public function __construct(Factory $factory)
        {
            $this->factory = $factory;
        }

        public function route(Request $request)
        {
            if (!$request->isGetRequest()) {
                return null;
            }

            $uri = $request->getRequestUri();
            $path = parse_url($uri, PHP_URL_PATH);

            switch ($path) {
                case '/':
                    return $this->factory->getHomeController();
                case '/editTranslationView':
                    return $this->factory->getEditTranslationViewController();
                case '/loginView':
                    return $this->factory->getLoginViewController();
                case '/logout':
                    return $this->factory->getLogoutController();
                default:
                    return null;
            }
        }
    }
}
