<?php

namespace Translation\Commands
{

    use Translation\Http\Request;
    use Translation\Http\Session;

    abstract class AbstractFormCommand implements FormCommandInterface
    {
        /** @var Session */
        private $session;

        public function __construct(Session $session)
        {
            $this->session = $session;
        }

        public function execute(Request $request): bool
        {
            $this->setFormValues($request);

            if ($this->session->isset('error')) {
                $this->session->deleteValue('error');
            }

            $this->validateRequest();
            if (!$this->hasErrors()) {
                $this->performAction();
                return true;
            }
            $this->repopulateForm();
            return false;
        }

        abstract protected function setFormValues(Request $request);
        abstract protected function validateRequest();
        abstract protected function performAction();
        abstract protected function repopulateForm();

        protected function hasErrors(): bool
        {
            if ($this->session->isset('error')) {
                return true;
            }
            return false;
        }

        protected function getSession(): Session
        {
            return $this->session;
        }
    }
}
