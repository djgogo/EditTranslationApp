<?php

namespace Translation\Authentication
{

    use Translation\Exceptions\UserTableGatewayException;
    use Translation\Gateways\UserTableDataGateway;
    use Translation\ParameterObjects\UserParameterObject;

    /**
     * @covers Translation\Authentication\Registrator
     * @uses Translation\Gateways\UserTableDataGateway
     */
    class RegistratorTest extends \PHPUnit_Framework_TestCase
    {
        /** @var UserTableDataGateway | \PHPUnit_Framework_MockObject_MockObject */
        private $gateway;

        /** @var UserParameterObject | \PHPUnit_Framework_MockObject_MockObject */
        private $parameterObject;

        /** @var Registrator */
        private $registrator;

        protected function setUp()
        {
            $this->gateway = $this->getMockBuilder(UserTableDataGateway::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->parameterObject = $this->getMockBuilder(UserParameterObject::class)
                 ->disableOriginalConstructor()
                 ->getMock();

            $this->registrator = new Registrator($this->gateway);
        }

        public function testUserCanBeRegistered()
        {
            $this->gateway
                ->expects($this->once())
                ->method('insert')
                ->with($this->parameterObject)
                ->willReturn(true);
            
            $this->assertTrue($this->registrator->register($this->parameterObject));
        }

        public function testUserCatchesExceptionIfRegistrationFails()
        {
            $this->gateway
                ->expects($this->once())
                ->method('insert')
                ->with($this->parameterObject)
                ->willThrowException(new UserTableGatewayException());

            $this->assertFalse($this->registrator->register($this->parameterObject));
        }

        public function testUserCanBeFound()
        {
            $username = 'Harry Potter';

            $this->gateway
                ->expects($this->once())
                ->method('findUserByUsername')
                ->with($username)
                ->willReturn(true);

            $this->assertTrue($this->registrator->usernameExists($username));
        }
    }
}
