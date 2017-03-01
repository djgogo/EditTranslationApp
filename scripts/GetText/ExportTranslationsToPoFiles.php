<?php
use Translation\Configuration\Configuration;
use Translation\Factories\PDOFactory;
use Translation\GetText\MySqlToPoExporter;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Konfiguration
 */
$configuration = new Configuration(__DIR__ . '/../../config/config.php');

/**
 * Database Handler und die Factory
 */
$pdoFactory = new PDOFactory(
    $configuration->getDatabaseHost(),
    $configuration->getDatabaseName(),
    $configuration->getDatabaseUser(),
    $configuration->getDatabasePassword(),
    $configuration->getDatabaseCharset()
);

/**
 * Neue PO-GetText Dateinamen
 */
$filenameGerman = $configuration->getGetTextExportPathGerman();
$filenameFrench = $configuration->getGetTextExportPathFrench();
/**
 * Export der Translations aus der MySql Datenbank
 */
$parser = new MySqlToPoExporter($pdoFactory->getDbHandler());
$parser->export();

/**
 * Schreibe Po-Dateien
 */
$parser->writeGermanPoGetTextFile($filenameGerman);
printf("Es wurden %d Datensätze erfolgreich in die Deutsche Po-Datei\n %s exportiert \n\n",
    $parser->getProcessedEntries(), $filenameGerman);

$parser->writeFrenchPoGetTextFile($filenameFrench);
printf("Es wurden %d Datensätze erfolgreich in die Französische Po-Datei\n %s exportiert \n",
    $parser->getProcessedEntries(), $filenameFrench);
