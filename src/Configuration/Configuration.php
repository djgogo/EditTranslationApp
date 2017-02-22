<?php

namespace Translation\Configuration
{

    use Translation\Exceptions\ConfigurationException;

    class Configuration
    {
        /** @var string */
        private $file;

        /** @var array */
        private $settings = [];

        /** @var bool */
        private $isLoaded = false;

        public function __construct(string $file)
        {
            $this->file = $file;
        }

        public function getValueFromConfig(string $key): string
        {
            $this->loadConfig();

            if (!isset($this->settings[$key])) {
                throw new ConfigurationException('Ein Konfigurationseintrag "' . $key . '" existiert nicht.');
            }

            return $this->settings[$key];
        }

        private function loadConfig()
        {
            /** die Konfiguration wird nur einmal geladen */
            if (!$this->isLoaded) {

                if (!is_readable($this->file)) {
                    throw new ConfigurationException('Die Konfigurations Datei "' . $this->file . '" konnte nicht gelesen werden.');
                }

                $this->isLoaded = true;
                $this->settings = require $this->file;
            }
        }

        public function isProduction(): bool
        {
            return $this->getValueFromConfig('production') === true;
        }

        public function getErrorLogPath(): string
        {
            return $this->getValueFromConfig('errorLogPath');
        }

        public function getTwigTemplatePath(): string
        {
            return $this->getValueFromConfig('twigPath');
        }

        public function getDatabaseHost(): string
        {
            return $this->getValueFromConfig('host');
        }

        public function getDatabaseName(): string
        {
            return $this->getValueFromConfig('database');
        }

        public function getDatabaseUser(): string
        {
            return $this->getValueFromConfig('user');
        }

        public function getDatabasePassword(): string
        {
            return $this->getValueFromConfig('password');
        }

        public function getDatabaseCharset(): string
        {
            return $this->getValueFromConfig('charset');
        }
    }
}
