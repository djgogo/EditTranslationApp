<?php
/**
 * TranslationApp Konfigurationsdatei
 */
$basePath = __DIR__ . '/../';

return [
    // Umgebung
    'production' => false,

    // Logger
    'errorLogPath' => $basePath . '/logs/error.log',

    // Twig Templates Pfad
    'twigPath' => $basePath . '/resources/views',

    // Datenbank
    'host' => 'localhost',
    'database' => 'i18n',
    'user' => 'AdminUser',
    'password' => 'A_User++',
    'charset' => 'utf8',

    // GetText Dateipfade
    'getTextFilePathGerman' => '/var/www/Competec/AlltronStore/locale/de_CH/LC_MESSAGES/messages.po',
    'getTextFilePathFrench' => '/var/www/Competec/AlltronStore/locale/fr_CH/LC_MESSAGES/messages.po',
    'getTextExportPathGerman' => $basePath . 'scripts/GetText/exportedGetTextFiles/de_CH/messages.po',
    'getTextExportPathFrench' => $basePath . 'scripts/GetText/exportedGetTextFiles/fr_CH/messages.po',
];
