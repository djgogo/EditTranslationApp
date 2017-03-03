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

            /**
             * löscht das Session-Cookie (PHPSESSID)
             * Sichere Löschung des Session-Cookies
             * Code Snippet von OWASP.org übernommen
             */
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), "", 1); // Sicher gehen dass das Cookie im Browser abläuft
                setcookie(session_name(), false); // Entfernt das Cookie
                unset($_COOKIE[session_name()]); // Entfernt das Cookie aus dem laufenden Programm
            }

            session_regenerate_id();
            $response->setRedirect('/');
            return;
        }
    }
}
