<?php

namespace Translation\Routers
{

    use Translation\Factories\Factory;
    use Translation\Http\Request;

    /**
     * @covers  Translation\Routers\GetRequestRouter
     * @uses    Translation\Factories\Factory
     * @uses    Translation\Http\Request
     */
    class GetRequestRouterTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Factory | \PHPUnit_Framework_MockObject_MockObject */
        private $factory;

        /** @var GetRequestRouter */
        private $getRequestRouter;

        protected function setUp()
        {
            $this->factory = $this->getMockBuilder(Factory::class)->disableOriginalConstructor()->getMock();
            $this->getRequestRouter = new GetRequestRouter($this->factory);
        }

        /**
         * @dataProvider provideData
         * @param string $path
         * @param string $instance
         */
        public function testHappyPath(string $path, string $instance)
        {
            $request = new Request(
                ['csrf' => 'testCsrf1234'],
                ['REQUEST_URI' => $path, 'REQUEST_METHOD' => 'GET']
            );

            $this->assertInstanceOf($instance, $this->getRequestRouter->route($request));
        }

        public function provideData(): array
        {
            return [
                ['/', \Translation\Controllers\HomeController::class],
            ];
        }

        public function testRouterReturnsNullIfRequestIsNotAGetRequest()
        {
            $request = new Request(
                ['csrf' => 'testCsrf1234'],
                ['REQUEST_URI' => '/translation', 'REQUEST_METHOD' => 'POST']
            );

            $this->assertEquals(null, $this->getRequestRouter->route($request));
        }

        public function testRouterReturnsNullIfInvalidRequestUri()
        {
            $request = new Request(
                ['csrf' => 'testCsrf1234'],
                ['REQUEST_URI' => '/invalid', 'REQUEST_METHOD' => 'GET']
            );

            $this->assertEquals(null, $this->getRequestRouter->route($request));
        }
    }
}
