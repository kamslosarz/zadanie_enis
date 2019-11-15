<?php

require '../vendor/autoload.php';

use App\App;
use App\AppException;

define('APP_DIR', dirname(__DIR__));
$code = isset($_GET['code']) ? $_GET['code'] : null;

try
{
    $app = new App();
    $app->list($code);
}
catch(AppException $exception)
{
    handleException($exception);
}
function handleException(Throwable $throwable)
{
    echo sprintf('%s %s %s', $throwable->getMessage(), PHP_EOL, $throwable->getTraceAsString());
}