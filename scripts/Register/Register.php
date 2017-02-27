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
 * Database Handler und die Factory
 */
$pdoFactory = new PDOFactory(
    $configuration->getDatabaseHost(),
    $configuration->getDatabaseName(),
    $configuration->getDatabaseUser(),
    $configuration->getDatabasePassword(),
    $configuration->getDatabaseCharset()
);
$factory  = new Factory($session, $pdoFactory, $configuration->getErrorLogPath());

/**
 * Parst die eingegebenen Shell Argumente in die $_GET Globale
 * - Falls tatsächlich Argumente eingegeben worden sind (0 ist der Name des Scripts)
 * - die Argumente schnappen ohne den ersten
 * - In ein Standard Query String umwandeln mit &
 * - mit parse_str die Daten in $_GET extrahieren
 */
if ($argc > 1) {
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
    $username = $_GET['username'];
    $password = $_GET['password'];
    $email = $_GET['email'];
} else {
    echo "Argumente (username, password, email) fehlen! \n";
    exit;
}

/**
 * Register with Registrator
 */
$registrator = new Registrator($factory->getUserTableGateway());
$userParameter = new UserParameterObject(
    $username,
    $password,
    $email
);

if ($registrator->register($userParameter)) {
    printf("Der Benutzer %s wurde erfolgreich für die Edit Translation App in der User Tabelle registriert. \n",
        $argv[1]);
} else {
    printf("Der Benutzer %s konnte nicht registriert werden! \n",
        $argv[1]);
}
