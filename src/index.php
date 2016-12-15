<?php

 require_once __DIR__ . '/vendor/autoload.php';

// init. des managers
require_once __DIR__ . '/Includes/managers.php';

// initialisation de l'application
require_once __DIR__ . '/init.php';

use Semeformation\Mvc\Cinema_crud\Controllers\HomeController;
use Semeformation\Mvc\Cinema_crud\Controllers\CinemaController;
use Semeformation\Mvc\Cinema_crud\Controllers\FavoriteController;
use Semeformation\Mvc\Cinema_crud\Controllers\ShowtimesController;
use Semeformation\Mvc\Cinema_crud\Controllers\MovieController;
use Semeformation\Mvc\Cinema_crud\Controllers\Router;

// on "assainit" les entrées
session_start();

// Initialisation du routeur et utilisation de sa méthode :
$routeur = new Router($logger);
$routeur->routeRequest();

