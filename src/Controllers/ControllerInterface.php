<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use Translation\Http\Response;

    interface ControllerInterface
    {
        public function execute(Request $request, Response $response);
    }
}
