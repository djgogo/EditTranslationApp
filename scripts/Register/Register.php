<?php

use Translation\Authentication\Registrator;
use Translation\Configuration\Configuration;
use Translation\Factories\Factory;
use Translation\Factories\PDOFactory;
use Translation\Http\Session;
use Translation\ParameterObjects\UserParameterObject;

require_once __DIR__ . '/../../bootstrap.php';
session_start();

/**
 * Konfiguration und Session Object
 */
$configuration = new Configuration(__DIR__ . '/../../config/config.php');
$session = new Session($_SESSION);

/**
 * Database Handler, die Factory und der Registrator
 */
$pdoFactory = new PDOFactory(
    $configuration->getDatabaseHost(),
    $configuration->getDatabaseName(),
    $configuration->getDatabaseUser(),
    $configuration->getDatabasePassword(),
    $configuration->getDatabaseCharset()
);
$factory  = new Factory($session, $pdoFactory, $configuration->getErrorLogPath());
$registrator = new Registrator($factory->getUserTableGateway());

/**
 * Parst die eingegebenen Shell Argumente username, password und email in die $_GET Globale
 * - Falls tatsächlich Argumente eingegeben worden sind ($argv[0] beinhaltet den Namen des Scripts)
 * - Alle Argumente schnappen ohne den ersten
 * - In ein Standard Query String umwandeln mit &
 * - mit parse_str die Daten in die Globale $_GET extrahieren
 */
if ($argc > 1) {

    parse_str(implode('&', array_slice($argv, 1)), $_GET);
    $username = $_GET['username'];
    $password = $_GET['password'];
    $email = $_GET['email'];

    if ($username !== '' && $registrator->usernameExists($username)) {
        printf("Der gewählte Benutzername %s existiert bereits. Verarbeitung wird abgebrochen. \n",
            $username);
        exit;
    }

} else {
    echo "Argumente (username, password, email) fehlen! \n";
    exit;
}

/**
 * Register with Registrator
 */
$userParameter = new UserParameterObject(
    $username,
    $password,
    $email
);

if ($registrator->register($userParameter)) {
    printf("Der Benutzer %s wurde erfolgreich für die Edit Translation App in der User Tabelle registriert. \n",
        $username);
} else {
    printf("Der Benutzer %s konnte nicht registriert werden! \n",
        $username);
}
