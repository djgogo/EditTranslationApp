<?php
use Translation\Factories\PDOFactory;
use Translation\GetText\MySqlToPoExporter;

require_once __DIR__ . '/../bootstrap.php';

/**
 * MySql Benutzerdaten und Standard Zeichensatz
 */
$host = 'localhost';
$dbName = 'i18n';
$user = 'AdminUser';
$password = 'A_User++';
$charset = 'utf8';

/**
 * Neue PO-GetText Dateinamen
 */
$filenameGerman = __DIR__ . '/exportedGetTextFiles/de_CH/messages.po';
$filenameFrench = __DIR__ . '/exportedGetTextFiles/fr_CH/messages.po';

/**
 * Database Handler
 */
$pdoFactory = new PDOFactory($host, $dbName, $user, $password, $charset);

/**
 * Export der Translations aus der MySql Datenbank
 */
$parser = new MySqlToPoExporter($pdoFactory->getDbHandler());
$parser->export();

/**
 * Schreibe Po-Dateien
 */
$parser->writeGermanPoGetTextFile($filenameGerman);
printf("Es wurden %d Datensätze erfolgreich in die Deutsche Po-Datei\n %s exportiert \n\n", $parser->getProcessedEntries(), $filenameGerman);

$parser->writeFrenchPoGetTextFile($filenameFrench);
printf("Es wurden %d Datensätze erfolgreich in die Französische Po-Datei\n %s exportiert \n", $parser->getProcessedEntries(), $filenameFrench);
