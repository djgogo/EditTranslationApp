<?php
use Translation\Configuration\Configuration;
use Translation\Exceptions\GetTextExportException;
use Translation\Exceptions\GetTextFileException;
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
$filePathGerman = $configuration->getGetTextGermanFilePath();
$filePathFrench = $configuration->getGetTextFrenchFilePath();

/**
 * Parse die Deutsche Po-Gettext Datei
 */
try {
    $parser = new PoParserGerman([]);
    $poData = $parser->parse($filePathGerman);
    printf("Es wurden %d Translations aus der deutschen Po-Datei verarbeitet. \n",
        $parser->getProcessedTranslations());

} catch (GetTextFileException $e) {
    printf("******* Import Fehler: %s \n", $e->getMessage());
    exit;
}

/**
 * Parse die Französische Po-Gettext Datei
 */
try {
    $parser = new PoParserFrench($poData);
    $poData = $parser->parse($filePathFrench);
    printf("Es wurden %d Translations aus der französichen Po-Datei verarbeitet. \n",
        $parser->getProcessedTranslations());

} catch (GetTextFileException $e) {
    printf("******* Import Fehler: %s \n", $e->getMessage());
    exit;
}

/**
 * Schreiben der PO-Daten in die MySql Datenbank
 */
try {
    $mySqlWriter = new PoToMySqlImporter($pdoFactory->getDbHandler());
    $mySqlWriter->import($poData);
    printf("Es wurden %d Datensätze erfolgreich in die MySql Datenbank %s importiert \n",
        $mySqlWriter->getProcessedEntries(),
        $configuration->getDatabaseName());

} catch (GetTextExportException $e) {
    printf("******* Import Fehler: %s \n", $e->getMessage());
    exit;
}
