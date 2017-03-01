<?php

namespace Translation\ParameterObjects
{

    /**
     * @covers Translation\ParameterObjects\TranslationParameterObject
     */
    class TranslationParameterObjectTest extends \PHPUnit_Framework_TestCase
    {
        /** @var TranslationParameterObject */
        private $translationParameter;

        protected function setUp()
        {
            $this->translationParameter = new TranslationParameterObject(
                'testId',
                'Test Message German',
                'Test Message French',
                '2017-02-24 00:00:00'
            );
        }

        /**
         * @dataProvider provideTranslationValues
         * @param $value
         * @param $method
         */
        public function testValuesCanBeRetrieved(string $value, string $method)
        {
            $this->assertEquals($value, $this->translationParameter->$method());
        }

        public function provideTranslationValues(): array
        {
            return [
                ['testId', 'getMsgId'],
                ['Test Message German', 'getMsgGerman'],
                ['Test Message French', 'getMsgFrench'],
                ['2017-02-24 00:00:00', 'getUpdated']
            ];
        }
    }
}
