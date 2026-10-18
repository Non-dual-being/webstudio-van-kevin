<?php

declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '0');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self'; img-src 'self' data:; font-src 'self'; object-src 'none'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'");
header('X-Robots-Tag: noindex, nofollow');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$send = static function (array $body, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
};

if (str_starts_with((string) $path, '/demo-api/')) {
    require __DIR__.'/bootstrap.php';
    $routes = ['rules' => 'GET', 'policy' => 'GET', 'programs' => 'GET', 'seed' => 'GET', 'dates' => 'POST', 'roster' => 'POST', 'quote' => 'POST', 'complete' => 'POST', 'change' => 'POST'];
    $route = substr($path, strlen('/demo-api/'));
    if (!isset($routes[$route])) {
        $send(['ok' => false, 'message' => 'Onbekende demo-API.'], 404);
    }
    if ($routes[$route] !== $method) {
        header('Allow: '.$routes[$route]);
        $send(['ok' => false, 'message' => 'Methode niet toegestaan.'], 405);
    }
    if (($_SERVER['QUERY_STRING'] ?? '') !== '' || (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 32768 || $_FILES !== []) {
        $send(['ok' => false, 'message' => 'Ongeldig demoverzoek.'], 400);
    }
    try {
        $clock = new Demo\DemoClock(getenv('DEMO_TODAY') ?: null);
        $context = Demo\Dashboard::context($_POST['demoContext'] ?? null);
        $app = new Demo\Application($clock, $context);
        $dashboard = new Demo\Dashboard($app);
        $data = match ($route) {
            'rules' => GeoFort\Validation\FormRules::getRulesForFrontend(),
            'policy' => GeoFort\Booking\BookingPolicy::getBookingPolicyForFrontend(),
            'programs' => GeoFort\Booking\BookingProgramConfig::forFrontend(),
            'seed' => $dashboard->seed(),
            'dates' => $app->dates(),
            'roster' => $app->roster($_POST),
            'quote' => $app->quote($_POST),
            'complete' => $dashboard->complete($_POST, $context ?? Demo\Seed::records($clock)),
            'change' => $dashboard->mutate($_POST, $context ?? Demo\Seed::records($clock)),
        };
        $send($route === 'complete' ? $data : ['ok' => true, 'data' => $data]);
    } catch (GeoFort\Validation\FieldValidationException $error) {
        $send(['ok' => false, 'type' => 'validation', 'fieldErrors' => [$error->getField() => $error->getMessage()]], 422);
    } catch (JsonException|TypeError) {
        $send(['ok' => false, 'type' => 'validation', 'fieldErrors' => ['demo' => 'Ongeldig demoverzoek.']], 400);
    } catch (Throwable) {
        // Geen exceptiontekst of formulierinhoud in response/logs.
        $send(['ok' => false, 'type' => 'server', 'code' => 500, 'message' => 'De demo kon dit niet controleren. Probeer opnieuw.'], 500);
    }
}

// Alleen buildassets en de ene demo-pagina; geen doorval naar PHP/projectbestanden.
$asset = is_string($path) && preg_match('~^/assets/[A-Za-z0-9_-]+\.(js|css|woff2?)$~D', $path, $matches);
$file = $path === '/' ? dirname(__DIR__).'/dist/index.html' : ($asset ? dirname(__DIR__).'/dist'.$path : null);
if ($method !== 'GET' || $file === null || !is_file($file)) {
    $send(['ok' => false, 'message' => 'Niet gevonden.'], 404);
}
$types = ['js' => 'text/javascript', 'css' => 'text/css', 'woff' => 'font/woff', 'woff2' => 'font/woff2'];
header('Content-Type: '.($asset ? $types[$matches[1]] : 'text/html; charset=utf-8'));
readfile($file);
