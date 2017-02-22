<?php
use Translation\Factories\PDOFactory;
use Translation\GetText\PoParserFrench;
use Translation\GetText\PoParserGerman;
use Translation\GetText\PoToMySqlImporter;
use Translation\GetText\PoParser;

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
 * Po Gettext Datei Pfade
 */
$filePathGerman = '/var/www/Competec/AlltronStore/locale/de_CH/LC_MESSAGES/messages.po';
$filePathFrench = '/var/www/Competec/AlltronStore/locale/fr_CH/LC_MESSAGES/messages.po';

/**
 * Database Handler
 */
$pdoFactory = new PDOFactory($host, $dbName, $user, $password, $charset);

/**
 * Parse die Deutsche Po-Gettext Datei
 */
$parser = new PoParserGerman($filePathGerman);
$poData = $parser->parse();

printf("Es wurden %d Translations aus der deutschen Po-Datei verarbeitet. \n", $parser->getProcessedTranslations());

/**
 * Parse die Französische Po-Gettext Datei
 */
$parser = new PoParserFrench($filePathFrench, $poData);
$poData = $parser->parse();

printf("Es wurden %d Translations aus der französichen Po-Datei verarbeitet. \n", $parser->getProcessedTranslations());

/**
 * Schreiben der PO-Daten in die MySql Datenbank
 */
$mySqlWriter = new PoToMySqlImporter($pdoFactory);
$mySqlWriter->import($poData);

printf("Es wurden %d Datensätze erfolgreich in die MySql Datenbank %s importiert \n", $mySqlWriter->getProcessedEntries(), $dbName);
