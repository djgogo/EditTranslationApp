<?php
use Translation\Configuration\Configuration;
use Translation\Factories\PDOFactory;
use Translation\GetText\PoParserFrench;
use Translation\GetText\PoParserGerman;
use Translation\GetText\PoToMySqlImporter;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Konfiguration
 */
$configuration = new Configuration(__DIR__ . '/../../config/config.php');

/**
 * Database Handler
 */
$pdoFactory = new PDOFactory(
    $configuration->getDatabaseHost(),
    $configuration->getDatabaseName(),
    $configuration->getDatabaseUser(),
    $configuration->getDatabasePassword(),
    $configuration->getDatabaseCharset()
);

/**
 * Po Gettext Datei Pfade
 */
$filePathGerman = '/var/www/Competec/AlltronStore/locale/de_CH/LC_MESSAGES/messages.po';
$filePathFrench = '/var/www/Competec/AlltronStore/locale/fr_CH/LC_MESSAGES/messages.po';

/**
 * Parse die Deutsche Po-Gettext Datei
 */
$parser = new PoParserGerman($filePathGerman);
$poData = $parser->parse();

printf("Es wurden %d Translations aus der deutschen Po-Datei verarbeitet. \n",
    $parser->getProcessedTranslations());

/**
 * Parse die Französische Po-Gettext Datei
 */
$parser = new PoParserFrench($filePathFrench, $poData);
$poData = $parser->parse();

printf("Es wurden %d Translations aus der französichen Po-Datei verarbeitet. \n",
    $parser->getProcessedTranslations());

/**
 * Schreiben der PO-Daten in die MySql Datenbank
 */
$mySqlWriter = new PoToMySqlImporter($pdoFactory->getDbHandler());
$mySqlWriter->import($poData);

printf("Es wurden %d Datensätze erfolgreich in die MySql Datenbank %s importiert \n",
    $mySqlWriter->getProcessedEntries(),
    $configuration->getDatabaseName());
