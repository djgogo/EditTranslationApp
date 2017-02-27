<?php

namespace Translation\Forms
{

    use Translation\Http\Session;

    /**
     * @covers Translation\Forms\FormPopulate
     * @uses Translation\Http\Session
     */
    class FormPopulateTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Session | \PHPUnit_Framework_MockObject_MockObject */
        private $session;

        /** @var FormPopulate */
        private $formPopulate;

        protected function setUp()
        {
            $this->session = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();

            $this->formPopulate = new FormPopulate($this->session);
        }

        public function testFormValuesCanBeSetAndRetrieved()
        {
            $this->formPopulate->set('formField', 'formValue');
            $this->assertEquals('formValue', $this->formPopulate->get('formField'));
        }

        public function testIfFormPopulateHasAValueReturnsRightBoolean()
        {
            $this->formPopulate->set('formField', 'formValue');
            $this->assertTrue($this->formPopulate->has('formField'));
        }

        public function testFormValueCanBeRemoved()
        {
            $this->formPopulate->set('formField', 'formValue');
            $this->assertTrue($this->formPopulate->has('formField'));

            $this->formPopulate->remove('formField');
            $this->assertFalse($this->formPopulate->has('formField'));
        }

        public function testGetFormValueReturnsEmptyStringIfNotFound()
        {
            $this->assertEquals('', $this->formPopulate->get('anyField'));
        }
    }
}
