<?php

namespace Translation\Routers
{
    use Translation\Factories\Factory;
    use Translation\Http\Request;

    class Error404Router implements RouterInterface
    {
        /** @var Factory */
        private $factory;

        public function __construct(Factory $factory)
        {
            $this->factory = $factory;
        }

        public function route(Request $request)
        {
            return $this->factory->getError404Controller();
        }
    }
}
