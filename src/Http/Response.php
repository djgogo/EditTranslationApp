<?php

namespace Translation\Http {

    use Translation\Entities\Translation;

    class Response
    {
        /** @var string */
        private $redirect;

        /** @var array */
        private $translations;

        /** @var Translation */
        private $translation;

        public function setRedirect(string $path)
        {
            $this->redirect = $path;
        }

        public function getRedirect(): string
        {
            return $this->redirect;
        }

        public function hasRedirect(): bool
        {
            return isset($this->redirect);
        }

        public function setTranslation(Translation $translation)
        {
            $this->translation = $translation;
        }

        public function getTranslation(): Translation
        {
            return $this->translation;
        }

        public function setTranslations(Translation ...$translations)
        {
            $this->translations = $translations;
        }

        public function getTranslations(): array
        {
            return $this->translations;
        }
    }
}
