<?php

namespace Translation\Http
{

    use Translation\Exceptions\RequestValueNotFoundException;

    /**
     * @covers Translation\Http\Request
     */
    class RequestTest extends \PHPUnit_Framework_TestCase
    {
        /** @var array */
        private $getRequest;

        /** @var array */
        private $server;

        /** @var Request */
        private $request;

        protected function setUp()
        {
            $this->getRequest = [
                'msgId' => 'testId'
            ];

            $this->server = [
                'REQUEST_URI' => '/updatetranslationview?id=FooBar',
                'REQUEST_METHOD' => 'GET',
                'PHP_AUTH_USER' => 'Translation'
            ];

            $this->request = new Request($this->getRequest, $this->server);
        }

        public function testRequestUriCanBeRetrieved()
        {
            $this->assertEquals($this->server['REQUEST_URI'], $this->request->getRequestUri());
        }

        public function testRequestMethodCanBeRetrieved()
        {
            $this->assertEquals($this->server['REQUEST_METHOD'], $this->request->getRequestMethod());
        }

        public function testIsPostRequestReturnsRightBoolean()
        {
            $this->assertFalse($this->request->isPostRequest());
        }

        public function testIsGetRequestReturnsRightBoolean()
        {
            $this->assertTrue($this->request->isGetRequest());
        }

        public function testHttpAuthUserCanBeCheckedIfLoggedIn()
        {
            $this->assertTrue($this->request->isLoggedIn());
        }

        public function testValueCanBeRetrieved()
        {
            $this->assertEquals('testId', $this->request->getValue('msgId'));
        }

        public function testRequestThrowsExceptionIfValueNotFound()
        {
            $this->expectException(RequestValueNotFoundException::class);
            $this->assertEquals('', $this->request->getValue('Wrong Key'));
        }
    }
}
