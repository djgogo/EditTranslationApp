<?php

namespace Translation\Routers
{

    use Translation\Factories\Factory;
    use Translation\Http\Request;
    use Translation\Http\Session;
    use Translation\Loggers\ErrorLogger;

    /**
     * @covers  Translation\Routers\PostRequestRouter
     * @uses    Translation\Factories\Factory
     * @uses    Translation\Http\Session
     * @uses    Translation\Http\Request
     * @uses    Translation\Loggers\ErrorLogger
     */
    class PostRequestRouterTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Factory | \PHPUnit_Framework_MockObject_MockObject */
        private $factory;

        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var PostRequestRouter */
        private $postRequestRouter;

        /** @var ErrorLogger | \PHPUnit_Framework_MockObject_MockObject */
        private $errorLogger;

        protected function setUp()
        {
            $this->factory = $this->getMockBuilder(Factory::class)->disableOriginalConstructor()->getMock();
            $this->session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
            $this->errorLogger = $this->getMockBuilder(ErrorLogger::class)->disableOriginalConstructor()->getMock();
            $this->postRequestRouter = new PostRequestRouter($this->factory, $this->session, $this->errorLogger);
        }

        /**
         * @dataProvider provideTestData
         * @param string $path
         * @param string $instance
         */
        public function testRouterRoutesCorrectlyHappyPath(string $path, string $instance)
        {
            $request = new Request(
                [],
                ['REQUEST_URI' => $path, 'REQUEST_METHOD' => 'POST']
            );

            $this->assertInstanceOf($instance, $this->postRequestRouter->route($request));
        }

        public function provideTestData(): array
        {
            return [
                ['/updateTranslation', \Translation\Controllers\UpdateTranslationController::class],
            ];
        }

        public function testRouterReturnsNullIfRequestIsNotAPostRequest()
        {
            $request = new Request(
                [],
                ['REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']
            );

            $this->assertEquals(null, $this->postRequestRouter->route($request));
        }

        public function testRouterReturnsNullIfInvalidRequestUri()
        {
            $request = new Request(
                [],
                ['REQUEST_URI' => '/invalid', 'REQUEST_METHOD' => 'POST']
            );

            $this->assertEquals(null, $this->postRequestRouter->route($request));
        }
    }
}
