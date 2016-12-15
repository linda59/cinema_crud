<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use  Semeformation\Mvc\Cinema_crud\Controllers\CinemaController;
//use  Semeformation\Mvc\Cinema_crud\Controllers\FavoriteController;
use  Semeformation\Mvc\Cinema_crud\Controllers\HomeController;
use  Semeformation\Mvc\Cinema_crud\Controllers\MovieController;
use  Semeformation\Mvc\Cinema_crud\Controllers\ShowtimesController;
use  \Psr\Log\LoggerInterface;

/**
*
*/
class Router{

    private $homeCtrl;
    private $favoriteCtrl;
    private $cinemaCtrl;
    private $movieCtrl;
    private $showtimeCtrl;

    function __construct(LoggerInterface $logger){
        $this -> logger = $logger;
        $this -> cinemaCtrl = new CinemaController($this -> logger);
        $this -> homeCtrl = new HomeController($this -> logger);
//        $this -> favoriteCtrl = new FavoriteController($this -> logger);
        $this -> showtimeCtrl = new ShowtimesController($this -> logger);
        $this -> movieCtrl = new MovieController($this -> logger);
    }
    public function routeRequest(){
        $sanitizedEntries = filter_input_array(INPUT_GET, ['action' => FILTER_SANITIZE_STRING]);

        if ($sanitizedEntries && $sanitizedEntries['action'] !== '') {
            // si l'action demandée est la liste des cinémas
            switch ($sanitizedEntries['action']) {
                // Activation de la route cinemasList
                case "cinemasList":
                $this -> cinemaCtrl->cinemasList();
                break;
                case "createUser":
                $this -> homeCtrl->createNewUser();
                break;
                case "cinemaShowtimes":
                $this -> showtimeCtrl->cinemaShowtimes();
                break;
                case "moviesList":
                $this -> movieCtrl->moviesList();
                break;
                case "movieShowtimes":
                $this -> showtimeCtrl->movieShowtimes();
                break;
                case "editShowtime":
                $this -> showtimeCtrl->editShowtime();
                break;
                case "deleteMovie":
                $this -> movieCtrl->deleteMovie();
                break;
                case "deleteShowtime":
                $this -> showtimeCtrl->deleteShowtime();
                break;
                case "deleteCinema":
                $this -> cinemaCtrl->deleteCinema();
                break;
//                case "deleteFavoriteMovie":
//                $this -> favoriteCtrl->deleteFavoriteMovie();
//                break;
                case "editCinema":
                $this -> cinemaCtrl->editCinema();
                break;
                case "editMovie":
                $this -> movieCtrl->editMovie();
                break;
//                case "editFavoriteMoviesList":
//                $this -> favoriteCtrl->editFavoriteMoviesList();
//                break;
//                case "editFavoriteMovie":
//                $this -> favoriteCtrl->editFavoriteMovie();
//                break;
                default:
                // Activation de la route par défaut (page d'accueil)
                $this -> homeCtrl->home();
            }
        } else {
            // Activation de la route par défaut (page d'accueil)
            $this -> homeCtrl->home();
        }
    }




}
