<?php

namespace Translation\Controllers
{

    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    class LoginViewControllerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var Request | \PHPUnit_Framework_MockObject_MockObject */
        private $request;

        /** @var Response | \PHPUnit_Framework_MockObject_MockObject */
        private $response;

        /** @var LoginViewController */
        private $loginViewController;

        protected function setUp()
        {
            $this->session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
            $this->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
            $this->response = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
            $this->loginViewController = new LoginViewController($this->session);
        }

        public function testControllerCanBeExecutedAndReturnsRightTemplate()
        {
            $this->session
                ->expects($this->once())
                ->method('isset')
                ->with('error')
                ->willReturn(false);

            $this->assertEquals('authentication/login.twig', $this->loginViewController->execute($this->request, $this->response));
        }

        public function testControllerReturnsRightTemplateIfExecutionHasAnError()
        {
            $this->session
                ->expects($this->once())
                ->method('isset')
                ->with('error')
                ->willReturn(true);

            $this->session
                ->expects($this->once())
                ->method('deleteValue')
                ->with('error');

            $this->assertEquals('authentication/login.twig', $this->loginViewController->execute($this->request, $this->response));
        }
    }
}
