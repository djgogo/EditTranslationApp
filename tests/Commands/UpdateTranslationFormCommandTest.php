<?php

namespace Translation\Commands
{

    use Translation\Exceptions\TranslationTableGatewayException;
    use Translation\Forms\FormError;
    use Translation\Forms\FormPopulate;
    use Translation\Gateways\TranslationTableDataGateway;
    use Translation\Http\Request;
    use Translation\Http\Session;
    use Translation\ParameterObjects\TranslationParameterObject;

    /**
     * @covers Translation\Commands\UpdateTranslationFormCommand
     * @covers Translation\Commands\AbstractFormCommand
     * @uses Translation\Gateways\TranslationTableDataGateway
     * @uses Translation\Http\Session
     * @uses Translation\Forms\FormPopulate
     * @uses Translation\Forms\FormError
     * @uses Translation\Http\Request
     * @uses Translation\ParameterObjects\TranslationParameterObject
     * @uses Translation\ValueObjects\MsgId
     */
    class UpdateTranslationFormCommandTest extends \PHPUnit_Framework_TestCase
    {
        /** @var TranslationTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $dataGateway;
        
        /** @var Session */
        private $session;
        
        /** @var FormPopulate */
        private $populate;
        
        /** @var FormError */
        private $error;
        
        /** @var \DateTime */
        private $dateTime;
        
        /** @var UpdateTranslationFormCommand */
        private $updateTranslationFormCommand;

        protected function setUp()
        {
            $this->dataGateway = $this->getMockBuilder(TranslationTableDataGateway::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->session = new Session([]);
            $this->populate = new FormPopulate($this->session);
            $this->error = new FormError($this->session);
            $this->dateTime = new \DateTime();
            $this->updateTranslationFormCommand = new UpdateTranslationFormCommand(
                $this->session,
                $this->dataGateway,
                $this->populate,
                $this->error,
                $this->dateTime
            );
        }

        /**
         * @dataProvider provideFormFields
         * @param string $field
         * @param string $expectedErrorMessage
         */
        public function testEmptyFormFieldsTriggersError(string $field, string $expectedErrorMessage)
        {
            $request = $this->getValidRequestValues();
            $request[$field] = '';
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get($field));
        }

        private function getValidRequestValues(): array
        {
            return [
                'msgId' => 'testID',
                'msgGerman' => 'Translation German',
                'msgFrench' => 'Translation French'
            ];
        }

        public function provideFormFields(): array
        {
            return [
                ['msgId', 'Die Translations-Id ist ungültig.'],
                ['msgGerman', 'Bitte geben Sie einen Deutschen Übersetzungstext ein.'],
                ['msgFrench', 'Bitte geben Sie einen Französischen Übersetzungstext ein.']
            ];
        }

        public function testInvalidMsgIdCatchesException()
        {
            $expectedErrorMessage = 'Die Translations-Id ist ungültig.';
            $request = $this->getValidRequestValues();
            $request['msgId'] = str_repeat('x', 256);
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get('msgId'));
        }

        public function testTooLongGermanMessageCatchesException()
        {
            $expectedErrorMessage = 'Der Text darf nicht länger als 1024 Zeichen sein.';
            $request = $this->getValidRequestValues();
            $request['msgGerman'] = str_repeat('x', 1025);
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get('msgGerman'));
        }

        public function testTooLongFrenchMessageCatchesException()
        {
            $expectedErrorMessage = 'Der Text darf nicht länger als 1024 Zeichen sein.';
            $request = $this->getValidRequestValues();
            $request['msgFrench'] = str_repeat('x', 1025);
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get('msgFrench'));
        }

        public function testTooShortGermanMessageCatchesException()
        {
            $expectedErrorMessage = 'Der Text sollte mindestens zwei Zeichen lang sein.';
            $request = $this->getValidRequestValues();
            $request['msgGerman'] = 'x';
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get('msgGerman'));
        }

        public function testTooShortFrenchMessageCatchesException()
        {
            $expectedErrorMessage = 'Der Text sollte mindestens zwei Zeichen lang sein.';
            $request = $this->getValidRequestValues();
            $request['msgFrench'] = 'x';
            $request = new Request($request, []);

            $this->assertFalse($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get('msgFrench'));
        }

        public function testTranslationCanBeUpdatedOnHappyPath()
        {
            $request = $this->getValidRequestValues();
            $request = new Request($request, []);

            $parameter = new TranslationParameterObject(
                $request->getValue('msgId'),
                $request->getValue('msgGerman'),
                $request->getValue('msgFrench'),
                $this->dateTime->format('Y-m-d H:i:s')
            );

            $this->dataGateway
                ->expects($this->once())
                ->method('update')
                ->with($parameter)
                ->willReturn(true);

            $this->assertTrue($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals('Datensatz wurde geändert.', $this->session->getValue('message'));
        }

        public function testExecutionWillDeleteSessionErrorIfSet()
        {
            $this->session->setValue('error', 'test');

            $request = $this->getValidRequestValues();
            $request = new Request($request, []);

            $parameter = new TranslationParameterObject(
                $request->getValue('msgId'),
                $request->getValue('msgGerman'),
                $request->getValue('msgFrench'),
                $this->dateTime->format('Y-m-d H:i:s')
            );

            $this->dataGateway
                ->expects($this->once())
                ->method('update')
                ->with($parameter)
                ->willReturn(true);

            $this->assertTrue($this->updateTranslationFormCommand->execute($request));
            $this->assertEquals('Datensatz wurde geändert.', $this->session->getValue('message'));
        }

        public function testIfUpdateTranslationFailsTriggersWarningMessage()
        {
            $request = $this->getValidRequestValues();
            $request = new Request($request, []);

            $this->dataGateway
                ->expects($this->once())
                ->method('update')
                ->willThrowException(new TranslationTableGatewayException());

            $this->updateTranslationFormCommand->execute($request);
            $this->assertEquals('Änderung fehlgeschlagen!', $this->session->getValue('warning'));
        }

        /**
         * @dataProvider formFieldDataProvider
         * @param $fieldName
         * @param $fieldValue
         */
        public function testFormFieldsCanBeRepopulated($fieldName, $fieldValue)
        {
            $this->populate->set($fieldName, $fieldValue);
            $this->assertSame($fieldValue, $this->session->getValue('populate')->get($fieldName));
        }

        public function formFieldDataProvider(): array
        {
            return [
                ['msgGerman', 'Translation German'],
                ['msgFrench', 'Translation French'],
            ];
        }
    }
}
