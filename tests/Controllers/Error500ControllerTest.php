<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use Translation\Http\Response;

    class Error500ControllerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Request | \PHPUnit_Framework_MockObject_MockObject */
        private $request;

        /** @var Response | \PHPUnit_Framework_MockObject_MockObject */
        private $response;

        protected function setUp()
        {
            $this->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
            $this->response = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
        }

        public function testExecutionReturns500Template()
        {
            $controller = new Error500Controller();
            $this->assertEquals('/templates/errors/500.twig', $controller->execute($this->request, $this->response));
        }

    }
}
