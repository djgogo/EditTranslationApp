<?php

use Translation\Configuration\Configuration;
use Translation\Factories\Factory;
use Translation\Factories\PDOFactory;
use Translation\Http\Request;
use Translation\Http\Response;
use Translation\Http\Session;
use Translation\ValueObjects\Token;

require __DIR__ . '/src/autoload.php';
require __DIR__ . '/vendor/autoload.php';

/**
 * Konfiguration
 */
$configuration = new Configuration(__DIR__ . '/config/config.php');

/**
 * nur während der Entwicklungsphase oder debugging
 */
if (!$configuration->isProduction()) {
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
}

/**
 * Session Sicherheit - Session Hi-Jacking Prävention
 */
// Zugriff mit Browser Script Sprachen wie Javascript nicht mehr möglich
ini_set('session.cookie_httponly', 1);
// Das Cookie wird nur noch über eine sichere Leitung gesendet
ini_set('session.cookie_secure', 1);

session_start();

// Regelmässiges Regenerieren der Session Id und löschen der Alten erschwert es Hi-Jackers umso mehr
session_regenerate_id(true);

/**
 * CSRF Protection Token
 */
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = new Token();
}

/**
 * Templating Engine (Twig)
 */
$loader = new Twig_Loader_Filesystem($configuration->getTwigTemplatePath());
$twig = new Twig_Environment($loader, ['cache' => false]);

/**
 * HTTP relevante Objekte
 */
$session = new Session($_SESSION);
$request = new Request($_REQUEST, $_SERVER);
$response = new Response();

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
 * Router and Controller für die Ausführung
 */
$routers = $factory->getRouters();
foreach ($routers as $router) {
    /** @var $router Translation\Routers\RouterInterface */
    $controller = $router->route($request);
    if ($controller !== null) {
        break;
    }
}

/**
 * View / Ausgabe
 * @var $controller Translation\Controllers\ControllerInterface
 */
$view = $controller->execute($request, $response);

/**
 * Rendern oder Weiterleiten
 */
if ($response->hasRedirect()) {
    $_SESSION = $session->getSessionData();
    header('Location: ' . $response->getRedirect(), 302);
    exit();
}
echo $twig->render($view, array('request' => $request, 'session' => $session, 'response' => $response));
$_SESSION = $session->getSessionData();

