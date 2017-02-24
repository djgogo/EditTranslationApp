<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use TRanslation\Http\Response;

    class Error404Controller implements ControllerInterface
    {
        public function execute(Request $request, Response $response): string
        {
            return '/templates/errors/404.twig';
        }
    }
}
