<?php

require __DIR__ . '/Includes/functions.php';



use Monolog\Logger;
use Monolog\Handler\StreamHandler;


// Création du logger
$logger = new Logger("Functions");
$logger->pushHandler(new StreamHandler(dirname(__DIR__) . '/logs/functions.log'));

// On utilise un gérant d'exceptions
set_error_handler("exception_error_handler");



