<?php

namespace Translation\Controllers {

    use Translation\Http\Request;
    use Translation\Http\Response;

    /**
     * @codeCoverageIgnore
     */
    class LogoutController implements ControllerInterface
    {
        public function execute(Request $request, Response $response)
        {
            /** Die gesamte Session Data löschen */
            session_destroy();

            /** löscht das Session-Cookie (PHPSESSID) */
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), "", 1); // Sicher gehen dass das Cookie im Browser abläuft
                setcookie(session_name(), false); // Entfernt das Cookie
                unset($_COOKIE[session_name()]); // Entfernt das Cookie von der Applikation
            }

            $response->setRedirect('/');
            return;
        }
    }
}
