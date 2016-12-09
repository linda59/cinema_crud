<?php

use Semeformation\Mvc\Cinema_crud\includes\DBFunctions;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Semeformation\Mvc\Cinema_crud\models\Utilisateur;
use Semeformation\Mvc\Cinema_crud\models\Cinema;
use Semeformation\Mvc\Cinema_crud\models\Film;
use Semeformation\Mvc\Cinema_crud\models\Prefere;
use Semeformation\Mvc\Cinema_crud\models\Seance;

// Création du logger
$logger = new Logger("Functions");
$logger->pushHandler(new StreamHandler(dirname(__DIR__) . './logs/functions.log'));
//$fctManager = new DBFunctions($logger);
$utilisateursMgr = new Utilisateur($logger);

$fctCinema = new Cinema($logger);

$fctFilm = new Film($logger);

$fctPrefere = new Prefere();


$fctSeance = new Seance($logger);

