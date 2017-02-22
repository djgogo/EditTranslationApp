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
];
