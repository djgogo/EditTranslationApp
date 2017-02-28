<?php

namespace Translation\Commands
{

    use Translation\Authentication\Authenticator;
    use Translation\Forms\FormError;
    use Translation\Forms\FormPopulate;
    use Translation\Http\Request;
    use Translation\Http\Session;

    /**
     * @covers Translation\Commands\AuthenticationFormCommand
     * @covers Translation\Commands\AbstractFormCommand
     * @uses Translation\Authentication\Authenticator
     * @uses Translation\Http\Session
     * @uses Translation\Forms\FormPopulate
     * @uses Translation\Forms\FormError
     * @uses Translation\Http\Request
     * @uses Translation\ParameterObjects\UserParameterObject
     * @uses Translation\ValueObjects\Password
     * @uses Translation\ValueObjects\Username
     */
    class AuthenticationFormCommandTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Authenticator | \PHPUnit_Framework_MockObject_MockObject */
        private $authenticator;

        /** @var Session */
        private $session;

        /** @var FormPopulate */
        private $populate;

        /** @var FormError */
        private $error;

        /** @var AuthenticationFormCommand */
        private $authenticationFormCommand;

        protected function setUp()
        {
            $this->authenticator = $this->getMockBuilder(Authenticator::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->session = new Session([]);
            $this->populate = new FormPopulate($this->session);
            $this->error = new FormError($this->session);
            $this->authenticationFormCommand = new AuthenticationFormCommand(
                $this->authenticator,
                $this->session,
                $this->populate,
                $this->error
            );
        }

        /**
         * @dataProvider provideFormFields
         * @param $fieldToEmpty
         * @param $expectedErrorMessage
         */
        public function testEmptyFormFieldsTriggersError($fieldToEmpty, $expectedErrorMessage)
        {
            $request = ['username' => 'testUser', 'password' => '123456'];
            $request[$fieldToEmpty] = '';
            $request = new Request($request, array());

            $this->assertFalse($this->authenticationFormCommand->execute($request));
            $this->assertEquals($expectedErrorMessage, $this->session->getValue('error')->get($fieldToEmpty));
        }

        public function provideFormFields(): array
        {
            return [
                ['username', 'Bitte geben Sie einen Benutzernamen ein.'],
                ['password', 'Bitte geben Sie ein Passwort ein.'],
            ];
        }

        public function testUserCanBeAuthenticated()
        {
            $request = ['username' => 'testUser', 'password' => '123456'];
            $request = new Request($request, array());

            $this->authenticator
                ->expects($this->once())
                ->method('authenticate')
                ->with($request->getValue('username'), $request->getValue('password'))
                ->willReturn(true);

            $this->assertTrue($this->authenticationFormCommand->execute($request));
            $this->assertEquals('Herzlich Willkommen - Sie können nun Einträge bearbeiten', $this->session->getValue('message'));
        }

        public function testExecutionWillDeleteSessionErrorIfSet()
        {
            $this->session->setValue('error', 'test');

            $request = ['username' => 'testUser', 'password' => '123456'];
            $request = new Request($request, array());

            $this->authenticator
                ->expects($this->once())
                ->method('authenticate')
                ->with($request->getValue('username'), $request->getValue('password'))
                ->willReturn(true);

            $this->assertTrue($this->authenticationFormCommand->execute($request));
            $this->assertEquals('Herzlich Willkommen - Sie können nun Einträge bearbeiten', $this->session->getValue('message'));
        }

        public function testAuthenticationFailsWithWrongCredentials()
        {
            $request = ['username' => 'testUser', 'password' => 'wrong Password'];
            $request = new Request($request, array());

            $this->authenticator
                ->expects($this->once())
                ->method('authenticate')
                ->with($request->getValue('username'), $request->getValue('password'))
                ->willReturn(false);

            $this->assertTrue($this->authenticationFormCommand->execute($request));
            $this->assertEquals('Log-In fehlgeschlagen!', $this->session->getValue('warning'));
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

        public function formFieldDataProvider() : array
        {
            return [
                ['username', 'testUser'],
                ['password', '123456'],
            ];
        }
    }
}
