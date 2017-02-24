<?php

namespace Translation\Controllers {

    use Translation\Entities\Translation;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    /**
     * @covers Translation\Controllers\HomeController
     * @uses Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Http\Request
     * @uses Translation\Http\Response
     * @uses Translation\Http\Session
     */
    class HomeControllerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Request | \PHPUnit_Framework_MockObject_MockObject */
        private $request;

        /** @var Response | \PHPUnit_Framework_MockObject_MockObject */
        private $response;

        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var TranslationTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $dataGateway;

        /** @var HomeController */
        private $homeController;

        /** @var Translation */
        private $translation;

        protected function setUp()
        {
            $this->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
            $this->response = $this->getMockBuilder(Response::class)->disableOriginalConstructor()->getMock();
            $this->session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
            $this->dataGateway = $this->getMockBuilder(TranslationTableDataGateway::class)->disableOriginalConstructor()->getMock();
            $this->translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();

            $this->homeController = new HomeController($this->session, $this->dataGateway);
        }

        public function testDefaultCaseCanBeExecutedAndReturnsHomeTemplate()
        {
            $this->request
                ->expects($this->at(0))
                ->method('hasValue')
                ->with('sort')
                ->willReturn(false);

            $this->request
                ->expects($this->at(1))
                ->method('hasValue')
                ->with('search')
                ->willReturn(false);

            $this->response
                ->expects($this->once())
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslations')
                ->willReturn([$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslations')
                ->willReturn(array());

            $this->assertEquals('home.twig', $this->homeController->execute($this->request, $this->response));
        }

        public function testSortAscendingCanBeExecuted()
        {
            $this->request
                ->expects($this->at(0))
                ->method('hasValue')
                ->with('sort')
                ->willReturn(true);

            $this->request
                ->expects($this->once())
                ->method('getValue')
                ->with('sort')
                ->willReturn('ASC');

            $this->response
                ->expects($this->once())
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslationsOrderedByUpdated')
                ->willReturn([$this->translation]);

            $this->assertEquals('home.twig', $this->homeController->execute($this->request, $this->response));
        }

        public function testSortDescendingCanBeExecuted()
        {
            $this->request
                ->expects($this->at(0))
                ->method('hasValue')
                ->with('sort')
                ->willReturn(true);

            $this->request
                ->expects($this->exactly(2))
                ->method('getValue')
                ->with('sort')
                ->willReturn('DESC');

            $this->response
                ->expects($this->once())
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslationsOrderedByUpdated')
                ->willReturn([$this->translation]);

            $this->assertEquals('home.twig', $this->homeController->execute($this->request, $this->response));
        }

        public function testExecutionWithSearchValueWorks()
        {
            $this->request
                ->expects($this->at(0))
                ->method('hasValue')
                ->with('sort')
                ->willReturn(false);

            $this->response
                ->expects($this->at(0))
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslations')
                ->willReturn([$this->translation]);

            $this->request
                ->expects($this->at(1))
                ->method('hasValue')
                ->with('search')
                ->willReturn(true);

            $this->response
                ->expects($this->at(1))
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getSearchedTranslation')
                ->willReturn([$this->translation]);

            $this->request
                ->expects($this->at(2))
                ->method('getValue')
                ->with('search')
                ->willReturn('search String');

            $this->assertEquals('home.twig', $this->homeController->execute($this->request, $this->response));
        }
    }
}
