<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    class LoginViewController implements ControllerInterface
    {
        /** @var Session */
        private $session;

        public function __construct(Session $session)
        {
            $this->session = $session;
        }

        public function execute(Request $request, Response $response)
        {
            if ($this->session->isset('error')) {
                $this->session->deleteValue('error');
            }

            return 'authentication/login.twig';
        }
    }
}
