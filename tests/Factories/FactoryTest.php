<?php

namespace Translation\Factories
{

    use Translation\Http\Session;

    /**
     * @covers Translation\Factories\Factory
     * @uses Translation\Http\Session
     * @uses Translation\Controllers\HomeController
     * @uses Translation\Controllers\EditTranslationViewController
     * @uses Translation\Controllers\UpdateTranslationController
     * @uses Translation\Controllers\Error404Controller
     * @uses Translation\Controllers\Error500Controller
     * @uses Translation\Controllers\LoginViewController
     * @uses Translation\Controllers\LoginController
     * @uses Translation\Controllers\LogoutController
     * @uses Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Gateways\UserTableDataGateway
     * @uses Translation\Commands\UpdateTranslationFormCommand
     * @uses Translation\Commands\AuthenticationFormCommand
     * @uses Translation\Forms\FormError
     * @uses Translation\Forms\FormPopulate
     * @uses Translation\Authentication\Authenticator
     * @uses Translation\Loggers\ErrorLogger
     * @uses Translation\Routers\GetRequestRouter
     * @uses Translation\Routers\PostRequestRouter
     * @uses Translation\Routers\Error404Router
     * @uses Translation\Commands\AbstractFormCommand
     */
    class FactoryTest extends \PHPUnit_Framework_TestCase
    {
        /** @var PDOFactory | \PHPUnit_Framework_MockObject_MockObject */
        private $pdoFactory;

        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var Factory */
        private $factory;

        protected function setUp()
        {
            $this->pdoFactory = $this->getMockBuilder(PDOFactory::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->session = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->factory = new Factory($this->session, $this->pdoFactory, 'errorPath');
        }

        public function testDatabaseCanBeRetrieved()
        {
            $this->assertInstanceOf(\PDO::class, $this->factory->getDatabase());
        }

        /**
         * @dataProvider provideRouterNames
         * @param int $router
         * @param string $instance
         */
        public function testRouterCanBeRetrieved(int $router, string $instance)
        {
            $this->assertInstanceOf($instance, $this->factory->getRouters()[$router]);
        }

        public function provideRouterNames(): array
        {
            return [
                [0, \Translation\Routers\GetRequestRouter::class],
                [1, \Translation\Routers\PostRequestRouter::class],
                [2, \Translation\Routers\Error404Router::class]
            ];
        }

        /**
         * @dataProvider provideMethodAndInstanceNames
         * @param string $method
         * @param string $instance
         */
        public function testInstancesCanBeCreated(string $method, string $instance)
        {
            $this->assertInstanceOf($instance, call_user_func_array([$this->factory, $method], []));
        }

        public function provideMethodAndInstanceNames()
        {
            return [
                ['getHomeController', \Translation\Controllers\HomeController::class],
                ['getEditTranslationViewController', \Translation\Controllers\EditTranslationViewController::class],
                ['getUpdateTranslationController', \Translation\Controllers\UpdateTranslationController::class],
                ['getError404Controller', \Translation\Controllers\Error404Controller::class],
                ['getError500Controller', \Translation\Controllers\Error500Controller::class],
                ['getLoginViewController', \Translation\Controllers\LoginViewController::class],
                ['getLoginController', \Translation\Controllers\LoginController::class],
                ['getLogoutController', \Translation\Controllers\LogoutController::class],
                ['getTranslationTableGateway', \Translation\Gateways\TranslationTableDataGateway::class],
                ['getUserTableGateway', \Translation\Gateways\UserTableDataGateway::class],
                ['getUpdateTranslationFormCommand', \Translation\Commands\UpdateTranslationFormCommand::class],
                ['getAuthenticationFormCommand', \Translation\Commands\AuthenticationFormCommand::class],
                ['getFormError', \Translation\Forms\FormError::class],
                ['getFormPopulate', \Translation\Forms\FormPopulate::class],
                ['getAuthenticator', \Translation\Authentication\Authenticator::class],
                ['getErrorLogger', \Translation\Loggers\ErrorLogger::class],
            ];
        }

    }
}
