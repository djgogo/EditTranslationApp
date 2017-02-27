<?php

namespace Translation\Controllers
{

    use Translation\Entities\Translation;
    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Forms\FormPopulate;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;
    use Translation\Http\Session;

    /**
     * @covers Translation\Controllers\EditTranslationViewController
     * @uses Translation\Entities\Translation
     * @uses Translation\Forms\FormPopulate
     * @uses Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Http\Request
     * @uses Translation\Http\Response
     * @uses Translation\Http\Session
     */
    class EditTranslationViewControllerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Request | \PHPUnit_Framework_MockObject_MockObject */
        private $request;
        
        /** @var Response | \PHPUnit_Framework_MockObject_MockObject */
        private $response;
        
        /** @var Translation | \PHPUnit_Framework_MockObject_MockObject */
        private $translation;
        
        /** @var TranslationTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $dataGateway;
        
        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;
        
        /** @var FormPopulate | \PHPUnit_Framework_MockObject_MockObject */
        private $populate;
        
        /** @var EditTranslationViewController */
        private $editTranslationViewController;

        protected function setUp()
        {
            $this->request = $this->getMockBuilder(Request::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->response = $this->getMockBuilder(Response::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->translation = $this->getMockBuilder(Translation::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->dataGateway = $this->getMockBuilder(TranslationTableDataGateway::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->session = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->populate = $this->getMockBuilder(FormPopulate::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->editTranslationViewController = new EditTranslationViewController(
                $this->session,
                $this->dataGateway,
                $this->populate
            );
        }

        public function testControllerCanBeExecutedAndReturnsRightTemplate()
        {
            $this->response
                ->expects($this->once())
                ->method('setTranslation')
                ->with($this->translation);

            $this->dataGateway
                ->expects($this->once())
                ->method('findTranslationById')
                ->with('testId')
                ->willReturn($this->translation);

            $this->request
                ->expects($this->once())
                ->method('getValue')
                ->with('msgId')
                ->willReturn('testId');

            $this->populate
                ->expects($this->at(0))
                ->method('set')
                ->with('msgGerman', 'German Translation');

            $this->response
                ->expects($this->exactly(2))
                ->method('getTranslation')
                ->willReturn($this->translation);

            $this->translation
                ->expects($this->once())
                ->method('getMsgGerman')
                ->willReturn('German Translation');

            $this->populate
                ->expects($this->at(1))
                ->method('set')
                ->with('msgFrench', 'French Translation');

            $this->translation
                ->expects($this->once())
                ->method('getMsgFrench')
                ->willReturn('French Translation');

            $this->session
                ->expects($this->once())
                ->method('isset')
                ->with('error')
                ->willReturn(false);

            $this->assertEquals('translations/editTranslation.twig', $this->editTranslationViewController->execute($this->request, $this->response));
        }

        public function testSessionErrorCanBeDeleted()
        {
            $this->response
                ->expects($this->once())
                ->method('setTranslation')
                ->with($this->translation);

            $this->dataGateway
                ->expects($this->once())
                ->method('findTranslationById')
                ->with('testId')
                ->willReturn($this->translation);

            $this->request
                ->expects($this->once())
                ->method('getValue')
                ->with('msgId')
                ->willReturn('testId');

            $this->populate
                ->expects($this->at(0))
                ->method('set')
                ->with('msgGerman', 'German Translation');

            $this->response
                ->expects($this->exactly(2))
                ->method('getTranslation')
                ->willReturn($this->translation);

            $this->translation
                ->expects($this->once())
                ->method('getMsgGerman')
                ->willReturn('German Translation');

            $this->populate
                ->expects($this->at(1))
                ->method('set')
                ->with('msgFrench', 'French Translation');

            $this->translation
                ->expects($this->once())
                ->method('getMsgFrench')
                ->willReturn('French Translation');

            $this->session
                ->expects($this->once())
                ->method('isset')
                ->with('error')
                ->willReturn(true);

            $this->session
                ->expects($this->once())
                ->method('deleteValue')
                ->with('error');

            $this->assertEquals('translations/editTranslation.twig', $this->editTranslationViewController->execute($this->request, $this->response));
        }

        public function testIfRequestHasValueIdButItsEmptyReturns404ErrorTemplate ()
        {
            $this->request
                ->expects($this->once())
                ->method('hasValue')
                ->with('msgId')
                ->willReturn(true);

            $this->request
                ->expects($this->once())
                ->method('getValue')
                ->with('msgId')
                ->willReturn('');

            $this->assertEquals('templates/errors/404.twig', $this->editTranslationViewController->execute($this->request, $this->response));
        }

        public function testSetTranslationWithInvalidMsgIdCatchesExceptionAndReturns500ErrorTemplate()
        {
            $this->response
                ->expects($this->once())
                ->method('setTranslation')
                ->willThrowException(new TranslationTableGatewayException());

            $this->assertEquals('templates/errors/500.twig', $this->editTranslationViewController->execute($this->request, $this->response));
        }
    }
}
