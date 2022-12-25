<?php

use Symfony\Component\Dotenv\Dotenv;

$dir = dirname(__DIR__);

require $dir . '/vendor/autoload.php';

if (file_exists($dir . '/config/bootstrap.php')) {
    require $dir . '/config/bootstrap.php';
} else {
    (new Dotenv())
        ->bootEnv($dir . '/.env');
}
