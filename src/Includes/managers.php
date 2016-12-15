<?php

use Semeformation\Mvc\Cinema_crud\Includes\DBFunctions;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;
use Semeformation\Mvc\Cinema_crud\Models\Cinema;
use Semeformation\Mvc\Cinema_crud\Models\Film;
use Semeformation\Mvc\Cinema_crud\Models\Prefere;
use Semeformation\Mvc\Cinema_crud\Models\Seance;


// Création du logger
$logger = new Logger("Functions");
$logger->pushHandler(new StreamHandler(dirname(__DIR__) . '/logs/functions.log'));
/*
//$fctManager = new DBFunctions($logger);
$utilisateursMgr = new Utilisateur($logger);

$fctCinema = new Cinema($logger);

$fctFilm = new Film($logger);

$fctPrefere = new Prefere();


$fctSeance = new Seance($logger);

*/
 
$managers = ['utilisateursMgr'=> new Utilisateur($logger), 
    'cinemasMgr'=> new Cinema($logger), 
    'seancesMgr'=> new Seance($logger), 
    'preferesMgr'=> new Prefere($logger), 
    'filmsMgr'=> new Film($logger)];

