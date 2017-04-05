<?php

namespace Translation\Http
{

    use Translation\Entities\Translation;

    /**
     * @covers Translation\Http\Response
     * @uses Translation\Entities\Translation
     */
    class ResponseTest extends \PHPUnit_Framework_TestCase
    {
        /** @var array */
        private $translations;

        /** @var Translation */
        private $translation;

        /** @var String */
        private $redirect;

        /** @var Response */
        private $response;

        protected function setUp()
        {
            $this->translations = [
                new Translation(
                    [
                        'msgId' => 'testId',
                        'msgGerman' => 'Foo German Text',
                        'msgFrench' => 'Foo French Text'
                    ]
                ),
                new Translation(
                    [
                        'msgId' => 'testId2',
                        'msgGerman' => 'Bar German Text',
                        'msgFrench' => 'Bar French Text'
                    ]
                )
            ];

            $this->translation = new Translation(
                [
                    'msgId' => 'testId',
                    'msgGerman' => 'Foo German Text',
                    'msgFrench' => 'Foo French Text'
                ]
            );

            $this->redirect = '/goSomeWhere';
            $this->response = new Response();
        }

        public function testTranslationCanBeSetAndRetrieved()
        {
            $this->response->setTranslation($this->translation);
            $this->assertEquals($this->translation, $this->response->getTranslation());
        }

        public function testTranslationsCanBeSetAndRetrieved()
        {
            $this->response->setTranslations(...$this->translations);
            $this->assertEquals($this->translations, $this->response->getTranslations());
        }

        public function testRedirectCanBeSetAndRetrieved()
        {
            $this->response->setRedirect($this->redirect);
            $this->assertEquals($this->redirect, $this->response->getRedirect());
        }

        public function testItCanBeCheckedIfRedirectHasBeenSet()
        {
            $this->response->setRedirect($this->redirect);
            $this->assertTrue($this->response->hasRedirect());
        }
    }
}
