<?php

namespace Translation\Forms
{

    use Translation\Http\Session;

    /**
     * @covers Translation\Forms\FormError
     * @uses Translation\Http\Session
     */
    class FormErrorTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var FormError */
        private $formError;

        protected function setUp()
        {
            $this->session = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->formError = new FormError($this->session);
        }

        public function testFormValuesCanBeSetAndRetrieved()
        {
            $this->formError->set('formField', 'formValue');
            $this->assertEquals('formValue', $this->formError->get('formField'));
        }

        public function testIfFormPopulateHasAValueReturnsRightBoolean()
        {
            $this->formError->set('formField', 'formValue');
            $this->assertTrue($this->formError->has('formField'));
        }

        public function testFormValueCanBeRemoved()
        {
            $this->formError->set('formField', 'formValue');
            $this->assertTrue($this->formError->has('formField'));

            $this->formError->remove('formField');
            $this->assertFalse($this->formError->has('formField'));
        }

        public function testGetFormValueReturnsEmptyStringIfNotFound()
        {
            $this->assertEquals('', $this->formError->get('anyField'));
        }
    }
}
