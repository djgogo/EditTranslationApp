<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use Translation\Http\Response;

    class Error500Controller implements ControllerInterface
    {
        public function execute(Request $request, Response $response)
        {
            return '/templates/errors/500.twig';
        }
    }
}
