<?php

 require_once __DIR__ . '/vendor/autoload.php';



// initialisation de l'application
require_once __DIR__ . '/init.php';

use Semeformation\Mvc\Cinema_crud\Controllers\Router;

// on "assainit" les entrées
session_start();

// Initialisation du routeur et utilisation de sa méthode :
$routeur = new Router($logger);
$routeur->routeRequest();
