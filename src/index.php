<?php

 require_once __DIR__ . '/vendor/autoload.php'; 
 
// init. des managers 
require_once __DIR__ . '/includes/managers.php';

// initialisation de l'application 
require_once __DIR__ . '/init.php';

// appel au contrôleur serviteur
require __DIR__ . '/controllers/controleur.php';
use Semeformation\Mvc\Cinema_crud\controllers\HomeController;
use Semeformation\Mvc\Cinema_crud\controllers\CinemaController;

// on "assainit" les entrées
session_start();

$homeCtrl   = new HomeController($logger);
$cinemaCtrl = new CinemaController($logger);

$sanitizedEntries = filter_input_array(INPUT_GET, ['action' => FILTER_SANITIZE_STRING]);

if ($sanitizedEntries && $sanitizedEntries['action'] !== '') {
// si l'action demandée est la liste des cinémas 
    switch ($sanitizedEntries['action']) {
        // Activation de la route cinemasList
        case "cinemasList":
            $cinemaCtrl->cinemasList($managers);
            break;
        case "createUser":
            $homeCtrl->createNewUser($managers);
            break;
        case "cinemaShowtimes":
            cinemaShowtimes($managers);
            break;
        case "moviesList":
            moviesList($managers);
            break;
        case "movieShowtimes":
            movieShowtimes($managers);
            break;
        case "editShowtime":
            editShowtime($managers);
            break;
        case "deleteMovie":
            deleteMovie($managers);
            break;
        case "deleteShowtime":
            deleteShowtime($managers);
            break;
        case "deleteCinema":
            $cinemaCtrl->deleteCinema($managers);
            break;
        case "deleteFavoriteMovie":
            deleteFavoriteMovie($managers);
            break;
        case "editCinema":
            $cinemaCtrl->editCinema($managers);
            break;
        case "editMovie":
            editMovie($managers);
            break;
        case "editFavoriteMoviesList":
            editFavoriteMoviesList($managers);
            break;
        case "editFavoriteMovie":
            editFavoriteMovie($managers);
            break;
        default:
            // Activation de la route par défaut (page d'accueil) 
            $homeCtrl->home($managers);
    }
} else {
    // Activation de la route par défaut (page d'accueil) 
$homeCtrl->home($managers);
}