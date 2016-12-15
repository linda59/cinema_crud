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

// on "assainit" les entrées
session_start();

$cinemaCtrl = new CinemaController($logger);
$homeCtrl = new HomeController($logger);
$favoriteCtrl = new FavoriteController($logger);
$showtimesCtrl = new ShowtimesController($logger);
$movieCtrl = new MovieController($logger);

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
            $showtimesCtrl->cinemaShowtimes($managers);
            break;
        case "moviesList":
            $movieCtrl->moviesList($managers);
            break;
        case "movieShowtimes":
            $showtimesCtrl->movieShowtimes($managers);
            break;
        case "editShowtime":
            $showtimesCtrl->editShowtime($managers);
            break;
        case "deleteMovie":
            $movieCtrl->deleteMovie($managers);
            break;
        case "deleteShowtime":
            $showtimesCtrl->deleteShowtime($managers);
            break;
        case "deleteCinema":
            $cinemaCtrl->deleteCinema($managers);
            break;
        case "deleteFavoriteMovie":
            $favoriteCtrl->deleteFavoriteMovie($managers);
            break;
        case "editCinema":
            $cinemaCtrl->editCinema($managers);
            break;
        case "editMovie":
            $movieCtrl->editMovie($managers);
            break;
        case "editFavoriteMoviesList":
            $favoriteCtrl->editFavoriteMoviesList($managers);
            break;
        case "editFavoriteMovie":
            $favoriteCtrl->editFavoriteMovie($managers);
            break;
        default:
            // Activation de la route par défaut (page d'accueil)
            $homeCtrl->home($managers);
    }
} else {
    // Activation de la route par défaut (page d'accueil)
    $homeCtrl->home($managers);
}
