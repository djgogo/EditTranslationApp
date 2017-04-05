<?php

namespace Translation\Entities
{
    /**
     * @covers Translation\Entities\Translation
     */
    class TranslationTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Translation */
        private $translation;

        /** @var \ReflectionClass */
        private $reflection;

        protected function setUp()
        {
            $this->translation = new Translation();
            $this->reflection = new \ReflectionClass($this->translation);
        }

        /**
         * @dataProvider provideTranslationValues
         * @param string $property
         * @param string $value
         * @param string $method
         */
        public function testTranslationTableValuesCanBeRetrieved(string $property, string $value, string $method)
        {
            $reflectionProperty = $this->reflection->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($this->translation, $value);

            $this->assertEquals($value, $this->translation->{$method}());
        }

        public function provideTranslationValues(): array
        {
            return [
                ['msgId', 'Foo English Tag', 'getMsgId'],
                ['msgGerman', 'Foo German Text', 'getMsgGerman'],
                ['msgFrench', 'Foo French Text', 'getMsgFrench'],
                ['created', '2017-02-24 00:00:00', 'getCreated'],
                ['updated', '2017-02-24 00:00:00', 'getUpdated']
            ];
        }
    }
}
