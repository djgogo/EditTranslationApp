<?php

namespace Translation\Factories
{

    use Translation\Authentication\Authenticator;
    use Translation\Commands\AuthenticationFormCommand;
    use Translation\Commands\UpdateTranslationFormCommand;
    use Translation\Controllers\EditTranslationViewController;
    use Translation\Controllers\Error404Controller;
    use Translation\Controllers\Error500Controller;
    use Translation\Controllers\HomeController;
    use Translation\Controllers\LoginController;
    use Translation\Controllers\LoginViewController;
    use Translation\Controllers\LogoutController;
    use Translation\Controllers\UpdateTranslationController;
    use Translation\Forms\FormError;
    use Translation\Forms\FormPopulate;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Gateways\UserTableDataGateway;
    use Translation\Http\Session;
    use Translation\Loggers\ErrorLogger;
    use Translation\Routers\Error404Router;
    use Translation\Routers\GetRequestRouter;
    use Translation\Routers\PostRequestRouter;

    class Factory
    {
        /** @var Session */
        private $session;

        /** @var PDOFactory */
        private $pdoFactory;

        /** @var string */
        private $errorLogPath;

        /** @var bool */
        private $loggerInstance = null;

        public function __construct(Session $session, PDOFactory $pdoFactory, string $errorLogPath)
        {
            $this->session = $session;
            $this->pdoFactory = $pdoFactory;
            $this->errorLogPath = $errorLogPath;
        }

        /**
         * Database
         */
        public function getDatabase(): \PDO
        {
            return $this->pdoFactory->getDbHandler();
        }

        /**
         * Routers
         */
        public function getRouters(): array
        {
            return [
                new GetRequestRouter($this),
                new PostRequestRouter($this, $this->session, $this->getErrorLogger()),
                new Error404Router($this),
            ];
        }

        /**
         * Controllers
         */
        public function getHomeController(): HomeController
        {
            return new HomeController($this->session, $this->getTranslationTableGateway());
        }

        public function getEditTranslationViewController(): EditTranslationViewController
        {
            return new EditTranslationViewController($this->session, $this->getTranslationTableGateway(), $this->getFormPopulate());
        }

        public function getUpdateTranslationController(): UpdateTranslationController
        {
            return new UpdateTranslationController($this->getUpdateTranslationFormCommand(), $this->getTranslationTableGateway());
        }

        public function getError404Controller(): Error404Controller
        {
            return new Error404Controller();
        }

        public function getError500Controller(): Error500Controller
        {
            return new Error500Controller();
        }

        public function getLoginViewController(): LoginViewController
        {
            return new LoginViewController($this->session);
        }

        public function getLoginController(): LoginController
        {
            return new LoginController($this->getAuthenticationFormCommand());
        }

        public function getLogoutController(): LogoutController
        {
            return new LogoutController();
        }

        /**
         * TableDataGateways
         */
        public function getTranslationTableGateway(): TranslationTableDataGateway
        {
            return new TranslationTableDataGateway($this->getDatabase(), $this->getErrorLogger());
        }

        public function getUserTableGateway(): UserTableDataGateway
        {
            return new UserTableDataGateway($this->getDatabase(), $this->getErrorLogger());
        }

        /**
         * FormCommands
         */
        public function getUpdateTranslationFormCommand(): UpdateTranslationFormCommand
        {
            return new UpdateTranslationFormCommand(
                $this->session,
                $this->getTranslationTableGateway(),
                $this->getFormPopulate(),
                $this->getFormError(),
                $this->getDateTime()
            );
        }

        public function getAuthenticationFormCommand(): AuthenticationFormCommand
        {
            return new AuthenticationFormCommand(
                $this->getAuthenticator(),
                $this->session,
                $this->getFormPopulate(),
                $this->getFormError()
            );
        }

        /**
         * Authentication
         */
        public function getAuthenticator(): Authenticator
        {
            return new Authenticator($this->getUserTableGateway());
        }

        /**
         * Forms Error Handling und Re-Population
         */
        public function getFormPopulate(): FormPopulate
        {
            return new FormPopulate($this->session);
        }

        public function getFormError(): FormError
        {
            return new FormError($this->session);
        }

        /**
         * Logger's
         */
        public function getErrorLogger(): ErrorLogger
        {
            if ($this->loggerInstance === null) {
                $this->loggerInstance = new ErrorLogger($this->getDateTime(), $this->errorLogPath);
            }
            return $this->loggerInstance;
        }

        /**
         * Local Time
         */
        private function getDateTime(): \DateTime
        {
            $datetime = new \DateTime();
            $datetime->setTimezone(new \DateTimeZone('Europe/Zurich'));
            return $datetime;
        }
    }
}
