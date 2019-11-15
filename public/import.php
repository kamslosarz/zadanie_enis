<?php

require '../vendor/autoload.php';

use App\App;
use App\AppException;
use App\Database\DatabaseFactory;

define('APP_DIR', dirname(__DIR__));
$filename = isset($argv[1]) ? $argv[1] : null;

try
{
    $app = new App();
    $app->import($filename);
}
catch(AppException $exception)
{
    handleException($exception);
}

function handleException(Throwable $throwable)
{
    DatabaseFactory::shutdown();
    echo sprintf('%s %s %s', $throwable->getMessage(), PHP_EOL, $throwable->getTraceAsString());
}