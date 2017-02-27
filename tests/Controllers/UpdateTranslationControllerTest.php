<?php

namespace Translation\Controllers
{

    use Translation\Commands\UpdateTranslationFormCommand;
    use Translation\Entities\Translation;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Response;

    /**
     * @covers Translation\Controllers\UpdateTranslationController
     * @uses   Translation\Http\Request
     * @uses   Translation\Http\Response
     * @uses   Translation\Commands\UpdateTranslationFormCommand
     * @uses   Translation\Gateways\TranslationTableDataGateway
     * @uses   Translation\Entities\Translation
     */
    class UpdateTranslationControllerTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Request | \PHPUnit_Framework_MockObject_MockObject */
        private $request;

        /** @var Response | \PHPUnit_Framework_MockObject_MockObject */
        private $response;

        /** @var Translation | \PHPUnit_Framework_MockObject_MockObject */
        private $translation;

        /** @var TranslationTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $dataGateway;

        /** @var UpdateTranslationFormCommand | \PHPUnit_Framework_MockObject_MockObject */
        private $updateTranslationFormCommand;

        /** @var UpdateTranslationController */
        private $updateTranslationController;

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
            $this->updateTranslationFormCommand = $this->getMockBuilder(UpdateTranslationFormCommand::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->updateTranslationController = new UpdateTranslationController(
                $this->updateTranslationFormCommand,
                $this->dataGateway
            );
        }

        public function testControllerCanBeExecutedAndSetsRightRedirect()
        {
            $this->updateTranslationFormCommand
                ->expects($this->once())
                ->method('execute')
                ->with($this->request)
                ->willReturn(true);

            $this->response
                ->expects($this->once())
                ->method('setTranslations')
                ->with(...[$this->translation]);

            $this->dataGateway
                ->expects($this->once())
                ->method('getAllTranslations')
                ->willReturn([$this->translation]);

            $this->response
                ->expects($this->once())
                ->method('setRedirect')
                ->with('/');

            $this->updateTranslationController->execute($this->request, $this->response);
        }

        public function testControllerRepopulateFormFieldsAndReturnsRightTemplateOnError()
        {
            $this->updateTranslationFormCommand
                ->expects($this->once())
                ->method('execute')
                ->with($this->request)
                ->willReturn(false);

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

            $this->assertEquals('translations/editTranslation.twig',
                $this->updateTranslationController->execute($this->request, $this->response));
        }

    }
}
