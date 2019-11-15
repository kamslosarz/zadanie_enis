<?php

require '../vendor/autoload.php';

use App\App;
use App\AppException;

define('APP_DIR', dirname(__DIR__));

try
{
    $app = new App();
    $app->lastImport();
}
catch(AppException $exception)
{
    handleException($exception);
}
function handleException(Throwable $throwable)
{
    echo sprintf('%s %s %s', $throwable->getMessage(), PHP_EOL, $throwable->getTraceAsString());
}