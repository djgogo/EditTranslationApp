<?php

namespace Translation\Configuration
{

    use Translation\Exceptions\ConfigurationException;

    /**
     * @covers Translation\Configuration\Configuration
     */
    class ConfigurationTest extends \PHPUnit_Framework_TestCase
    {
        /** @var Configuration */
        private $configuration;

        protected function setUp()
        {
            $this->configuration = new Configuration(__DIR__ . '/../../config/config.php');
        }

        /**
         * @dataProvider provideTestData
         * @param string $method
         * @param $result
         */
        public function testConfigurationEntriesCanAllBeRetrieved(string $method, $result)
        {
            $this->assertEquals($result, call_user_func_array([$this->configuration, $method], []));
        }

        public function provideTestData()
        {
            $basePath = '/var/www/EditTranslationApp/config/../';

            return [
                ['isProduction', false],
                ['getErrorLogPath', $basePath . '/logs/error.log'],
                ['getTwigTemplatePath', $basePath . '/resources/views'],
                ['getDatabaseHost', 'localhost'],
                ['getDatabaseName', 'i18n'],
                ['getDatabaseUser', 'AdminUser'],
                ['getDatabasePassword', 'A_User++'],
                ['getDatabaseCharset', 'utf8'],
                ['getGetTextGermanFilePath', '/var/www/Competec/AlltronStore/locale/de_CH/LC_MESSAGES/messages.po'],
                ['getGetTextFrenchFilePath', '/var/www/Competec/AlltronStore/locale/fr_CH/LC_MESSAGES/messages.po'],
                ['getGetTextExportPathGerman', $basePath . 'scripts/GetText/exportedGetTextFiles/de_CH/messages.po'],
                ['getGetTextExportPathFrench', $basePath . 'scripts/GetText/exportedGetTextFiles/fr_CH/messages.po'],
            ];
        }

        public function testConfigurationThrowsExceptionIfValueNotFound()
        {
            $this->expectException(ConfigurationException::class);
            $this->configuration->getValueFromConfig('invalidKey');
        }

        public function testConfigurationThrowsExceptionIfFileIsNotReadableWhileLoading()
        {
            $unreadableFile = new Configuration(__DIR__ . '/../../conf/notExistingFile.php');
            $this->expectException(ConfigurationException::class);
            $unreadableFile->getValueFromConfig('production');
        }
    }
}
