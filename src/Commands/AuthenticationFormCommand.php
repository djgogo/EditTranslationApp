<?php

namespace Translation\Commands {

    use Translation\Authentication\Authenticator;
    use Translation\Forms\FormError;
    use Translation\Forms\FormPopulate;
    use Translation\Http\Request;
    use Translation\Http\Session;
    use Translation\ValueObjects\Password;
    use Translation\ValueObjects\Username;

    class AuthenticationFormCommand extends AbstractFormCommand
    {
        /** @var Authenticator */
        private $authenticator;

        /** @var FormPopulate */
        private $populate;

        /** @var FormError */
        private $error;

        /** @var string */
        private $username;

        /** @var string */
        private $password;

        public function __construct(
            Authenticator $authenticator,
            Session $session,
            FormPopulate $formPopulate,
            FormError $error)
        {
            parent::__construct($session);

            $this->authenticator = $authenticator;
            $this->populate = $formPopulate;
            $this->error = $error;
        }

        protected function setFormValues(Request $request)
        {
            $this->username = $request->getValue('username');
            $this->password = $request->getValue('password');
        }

        protected function validateRequest()
        {
            try {
                new Username($this->username);
            } catch (\InvalidArgumentException $e) {
                $this->error->set('username', 'Bitte geben Sie einen gültigen Benutzernamen ein.');
            }

            if ($this->username === '') {
                $this->error->set('username', 'Bitte geben Sie einen Benutzernamen ein.');
            }

            try {
                new Password($this->password);
            } catch (\InvalidArgumentException $e) {
                $this->error->set('password', 'Bitte geben Sie ein gültiges Passwort ein.');
            }


            if ($this->password === '') {
                $this->error->set('password', 'Bitte geben Sie ein Passwort ein.');
            }
        }

        protected function performAction(): bool
        {
            if ($this->authenticator->authenticate($this->username, $this->password)) {
                $this->getSession()->setValue('message', 'Herzlich Willkommen - Sie können nun Einträge bearbeiten');
                $this->getSession()->setValue('user', $this->username);
                return true;
            }

            $this->getSession()->setValue('warning', 'Anmeldung fehlgeschlagen!');
            return false;
        }

        protected function repopulateForm()
        {
            if ($this->username !== '') {
                $this->populate->set('username', $this->username);
            }

            if ($this->password !== '') {
                $this->populate->set('password', $this->password);
            }
        }
    }
}

