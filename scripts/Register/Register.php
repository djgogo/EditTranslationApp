<?php

use Translation\Authentication\Registrator;
use Translation\Configuration\Configuration;
use Translation\Exceptions\InvalidEmailException;
use Translation\Factories\Factory;
use Translation\Factories\PDOFactory;
use Translation\Http\Session;
use Translation\ParameterObjects\UserParameterObject;
use Translation\ValueObjects\Email;
use Translation\ValueObjects\Password;
use Translation\ValueObjects\Username;

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
 * parse_str(implode('&', array_slice($argv, 1)), $_GET);
 * Parst die eingegebenen Shell Argumente username, password und email in die $_GET Globale
 * - Falls tatsächlich Argumente eingegeben worden sind ($argv[0] beinhaltet den Namen des Scripts)
 * - Alle Argumente schnappen ohne den ersten
 * - In ein Standard Query String umwandeln mit &
 * - mit parse_str die Daten in die Globale $_GET extrahieren
 */
if ($argc > 1) {

    /** aus Beispiel von php.net */
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
    $username = $_GET['username'];
    $password = $_GET['password'];
    $email = $_GET['email'];

    if ($username !== '' && $registrator->usernameExists($username)) {
        printf("Der gewählte Benutzername %s existiert bereits. Verarbeitung wird abgebrochen. \n",
            $username);
        exit;
    }

    $errorMessage = validateInput($username, $password, $email);
    if ($errorMessage !== '') {
        printf("%s \n", $errorMessage);
        exit;
    }

} else {
    echo "Parameter (username, password, email) fehlen! \n";
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
    printf("Der Benutzer %s wurde erfolgreich für die Edit Translation App in der i18n.users Tabelle registriert. \n",
        $username);
} else {
    printf("Der Benutzer %s konnte nicht registriert werden! \n",
        $username);
}

function validateInput($username, $password, $email): string
{
    $errorMessage = '';

    try {
        new Username($username);
    } catch (\InvalidArgumentException $e) {
        $errorMessage = 'Der Benutzername darf maximal 50 Zeichen lang sein.';
    }

    if ($username === '') {
        $errorMessage = 'Bitte geben Sie einen Benutzernamen an.';
    }

    try {
        new Password($password);
    } catch (\InvalidArgumentException $e) {
        $errorMessage = 'Das Passwort muss mindestens 6 und darf maximal 255 Zeichen lang sein.';
    }

    if ($password === '') {
        $errorMessage = 'Bitte geben Sie ein Passwort an.';
    }

    try {
        new Email($email);
    } catch (InvalidEmailException $e) {
        $errorMessage = 'Bitte geben Sie eine gültige E-Mail Adresse ein.';
    }

    if ($email === '') {
        $errorMessage = 'Bitte geben Sie eine E-Mail Adresse an.';
    }

    return $errorMessage;
}
